<?php

namespace App\Services\Shop;

use App\Models\Product;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Models\ProductContent;
use App\Models\ProductVariant;
use App\Http\Helpers\ImageUpload;
use App\Models\ProductOptionValue;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantValue;
use App\Models\ProductVariantSoldSerial;
use App\Models\ProductVariantSerialBatch;

class ProductService
{
    public static function store(Request $request)
    {
        $hasVariants = $request->boolean('has_variants');

        DB::beginTransaction();
        try {
            $thumbnail = null;
            $download_file = null;

            if ($request->hasFile('thumbnail')) {
                $thumbnail = ImageUpload::store(public_path('assets/img/product/'), $request->file('thumbnail'));
            }

            if (strtolower((string)$request->type) === 'digital') {
                if ($request->file_type === 'upload' && $request->hasFile('download_file')) {
                    $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
                }
            }

            $product = new Product();
            $product->thumbnail = $thumbnail;

            $product->has_variants = $hasVariants ? 1 : 0;
            $product->stock = $hasVariants ? 0 : (int)($request->stock ?? 0);
            $product->last_restock_qty = $hasVariants ? 0 : (int)($request->stock ?? 0);

            $product->sku = $hasVariants ? ($request->sku ?? null) : $request->sku;

            $product->current_price = $request->current_price ?? 0;
            $product->previous_price = $request->previous_price;

            $product->type = $request->type;
            $product->file_type = $request->file_type;
            $product->download_link = $request->download_link;
            $product->download_file = $download_file ?? null;

            $product->status = $request->status;
            $product->save();

            // slider images
            $sliders = $request->slider_image;
            if ($sliders) {
                $pis = SliderImage::findOrFail($sliders);
                foreach ($pis as $pi) {
                    $pi->item_id = $product->id;
                    $pi->save();
                }
            }

            // language contents
            self::storeOrUpdateLanguageContents($product, $request);

            // variants/options
            self::storeVariantsForProduct($product, $request, 'create');

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function update(Request $request, $id)
    {
        $hasVariants = $request->boolean('has_variants');

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            // thumbnail
            $thumbnail = $product->thumbnail;
            if ($request->hasFile('thumbnail')) {
                $thumbnail = ImageUpload::update(
                    public_path('assets/img/product/'),
                    $request->file('thumbnail'),
                    $product->thumbnail
                );
            }

            // download file
            $download_file = $product->download_file;
            if (strtolower((string)$product->type) === 'digital') {
                if ($request->file_type === 'upload' && $request->hasFile('download_file')) {
                    $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
                }
            }

            $product->thumbnail = $thumbnail;

            $product->has_variants = $hasVariants ? 1 : 0;
            $product->stock = $hasVariants ? 0 : (int)($request->stock ?? 0);
            $product->last_restock_qty = $hasVariants ? 0 : (int)($request->stock ?? 0);

            $product->sku = $hasVariants ? ($request->sku ?? null) : $request->sku;

            $product->current_price = $request->current_price ?? $product->current_price;
            $product->previous_price = $request->previous_price;

            $product->type = $request->type;
            $product->file_type = $request->file_type;
            $product->download_link = $request->download_link;
            $product->download_file = ($request->file_type == 'upload') ? $download_file : null;

            $product->status = $request->status;
            $product->save();

            // slider images
            $sliders = $request->slider_image;
            if ($sliders) {
                $pis = SliderImage::findOrFail($sliders);
                foreach ($pis as $pi) {
                    $pi->item_id = $product->id;
                    $pi->save();
                }
            }

            // language contents
            self::storeOrUpdateLanguageContents($product, $request);

            // variants/options (IMPORTANT: do NOT delete batches / sold serials)
            self::storeVariantsForProduct($product, $request, 'update');

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private static function storeOrUpdateLanguageContents(Product $product, Request $request): void
    {
        $languages = app('languages');
        foreach ($languages as $language) {
            $code = $language->code;

            $content = ProductContent::where('product_id', $product->id)
                ->where('language_id', $language->id)
                ->first();

            if (empty($content)) {
                $content = new ProductContent();
            }

            if (
                $language->is_default == 1 ||
                $request->filled($code . '_title') ||
                $request->filled($code . '_category_id') ||
                $request->filled($code . '_summary') ||
                $request->filled($code . '_description') ||
                $request->filled($code . '_meta_keyword') ||
                $request->filled($code . '_meta_description')
            ) {
                $metaKeywords = $request->input($code . '_meta_keyword');

                $content->language_id = $language->id;
                $content->product_id = $product->id;
                $content->category_id = $request->input($code . '_category_id');
                $content->subcategory_id = $request->input($code . '_subcategory_id');
                $content->title = $request->input($code . '_title');
                $content->slug = createSlug($request->input($code . '_title'));
                $content->summary = $request->input($code . '_summary');
                $content->description = $request->input($code . '_description');
                $content->meta_keyword = self::normalizeMetaKeywords($metaKeywords);
                $content->meta_description = $request->input($code . '_meta_description');
                $content->save();
            }
        }
    }

    /**
     * Upsert variants/options:
     * - create/update options and option values
     * - create/update variants and variant values
     * - create initial serial batch only when variant newly created and track_serial enabled
     * - do NOT delete serial batches / sold serials
     * - variants removed from request => mark inactive (status=0)
     */
    public static function storeVariantsForProduct(Product $product, Request $request, string $mode = 'update')
    {
        $hasVariants = $request->boolean('has_variants');

        if (!$hasVariants) {
            // If switched to no-variants, we should not destroy serial history.
            // Just deactivate existing variants & clear option mappings.
            ProductVariant::where('product_id', $product->id)->update(['status' => 0]);

            ProductVariantValue::whereIn(
                'variant_id',
                ProductVariant::where('product_id', $product->id)->pluck('id')
            )->delete();

            ProductOptionValue::whereIn('product_option_id', ProductOption::where('product_id', $product->id)->pluck('id'))->delete();
            ProductOption::where('product_id', $product->id)->delete();

            return;
        }

        $optionsInput  = $request->input('variant_options', []);
        $variantsInput = $request->input('variants', []);

        if (!is_array($optionsInput) || count($optionsInput) < 1) {
            throw new \Exception('Variant options are required.');
        }
        if (!is_array($variantsInput) || count($variantsInput) < 1) {
            throw new \Exception('Variants are required. Please generate variants first.');
        }

        // Build signatures before rebuilding options. Option IDs are regenerated each update.
        $existing = ProductVariant::where('product_id', $product->id)->get();
        $existingBySig = [];
        foreach ($existing as $ev) {
            $sig = self::variantSignatureFromDb($ev->id);
            if (!array_key_exists($sig, $existingBySig)) {
                $existingBySig[$sig] = $ev;
            }
        }

        // Rebuild options each time (safe) because they are definitions.
        ProductOptionValue::whereIn('product_option_id', ProductOption::where('product_id', $product->id)->pluck('id'))->delete();
        ProductOption::where('product_id', $product->id)->delete();

        $valueIdMap = []; // ["Color"]["Red"] => option_value_id

        foreach ($optionsInput as $pos => $opt) {
            $optName   = trim($opt['name'] ?? '');
            $valuesStr = $opt['values'] ?? '';

            if (!$optName) continue;

            $option = ProductOption::create([
                'product_id' => $product->id,
                'name'       => $optName,
                'position'   => (int)$pos,
            ]);

            $values = collect(explode(',', (string)$valuesStr))
                ->map(fn($v) => trim($v))
                ->filter()
                ->values();

            foreach ($values as $vpos => $v) {
                $val = ProductOptionValue::create([
                    'product_option_id' => $option->id,
                    'value'             => $v,
                    'position'          => (int)$vpos,
                ]);
                $valueIdMap[$optName][$v] = $val->id;
            }
        }

        $seenVariantIds = [];

        $variantImageDir = public_path('assets/img/product/variant/');

        foreach ($variantsInput as $i => $v) {
            $sku    = trim((string)($v['sku'] ?? ''));
            $price  = $v['price'] ?? null;
            $stock  = (int)($v['stock'] ?? 0); // treat as "initial qty" on create; on update do NOT override historical stock
            $status = (int)($v['status'] ?? 1);

            $serialStart = trim((string)($v['serial_start'] ?? ''));
            $serialEnd   = trim((string)($v['serial_end'] ?? ''));

            $trackSerial = ($serialStart !== '' || $serialEnd !== '');
            if ($trackSerial && ($serialStart === '' || $serialEnd === '')) {
                throw new \Exception('Serial start and end are required when tracking serials.');
            }

            // map from request => option_value_ids
            $map = json_decode($v['map'] ?? '{}', true) ?: [];
            $optionValueIds = [];

            foreach ($map as $optName => $optValue) {
                $optionValueId = $valueIdMap[$optName][$optValue] ?? null;
                if ($optionValueId) $optionValueIds[] = (int)$optionValueId;
            }

            sort($optionValueIds);
            $sig = self::variantSignatureFromMap($map);

            $variant = $existingBySig[$sig] ?? null;

            // Fallback for empty-map/default variants: match by SKU if possible.
            if (!$variant && $sig === '' && $sku !== '') {
                $variant = $existing->first(function ($ev) use ($sku, $seenVariantIds) {
                    return (string)$ev->sku === $sku && !in_array($ev->id, $seenVariantIds, true);
                });
            }

            if ($variant) {
                unset($existingBySig[$sig]);
            }

            $isNew = false;

            if (!$variant) {
                // create new variant
                $variant = ProductVariant::create([
                    'product_id'    => $product->id,
                    'sku'           => ($sku === '' ? null : $sku),
                    'price'         => ($price === '' || $price === null) ? null : (float)$price,
                    'stock'         => $stock, // initial stock only
                    'status'        => $status,
                    'track_serial'  => $trackSerial ? 1 : 0,
                    'serial_start'  => $trackSerial ? $serialStart : null,
                    'serial_end'    => $trackSerial ? $serialEnd : null,
                ]);
                $isNew = true;
            } else {
                // update existing variant safely (do NOT wipe batches/sold)
                $variant->sku = ($sku === '' ? $variant->sku : $sku);
                $variant->price = ($price === '' || $price === null) ? $variant->price : (float)$price;
                $variant->status = $status;

                // Allow toggling track_serial ON only if no sold serial yet (safety)
                if ($trackSerial) {
                    $hasSold = ProductVariantSoldSerial::where('variant_id', $variant->id)->exists();
                    if ($hasSold) {
                        // Keep existing serial config; don't allow changing ranges when already sold
                        $variant->track_serial = $variant->track_serial;
                    } else {
                        $variant->track_serial = 1;
                        $variant->serial_start = $serialStart;
                        $variant->serial_end   = $serialEnd;
                    }
                } else {
                    // turning off serial tracking is allowed, but we keep old data untouched
                    $variant->track_serial = 0;
                }

                // IMPORTANT: we do not overwrite stock here (use batches for real stock)
                $variant->save();
            }

            $seenVariantIds[] = $variant->id;

            // refresh pivot mapping: delete old values for this variant and re-insert
            ProductVariantValue::where('variant_id', $variant->id)->delete();

            foreach ($optionValueIds as $ovId) {
                ProductVariantValue::create([
                    'variant_id'      => $variant->id,
                    'option_value_id' => $ovId,
                ]);
            }

            $imageFile = $request->file("variants.$i.image");
            if ($imageFile) {
                $variant->image = ImageUpload::update($variantImageDir, $imageFile, $variant->image);
                $variant->save();
            }

            // Initial batch create only on NEW variant and trackSerial enabled
            if ($isNew && $trackSerial && $stock > 0) {
                self::assertSerialRangeHasStock($serialStart, $serialEnd, $stock);
                self::ensureNoBatchOverlap($variant->id, $serialStart, $serialEnd);

                $batchNo = 'INIT-' . $variant->id . '-' . now()->format('YmdHis');
                ProductVariantSerialBatch::create([
                    'variant_id'    => $variant->id,
                    'batch_no'      => $batchNo,
                    'serial_start'  => $serialStart,
                    'serial_end'    => $serialEnd,
                    'qty'           => $stock,
                    'sold_qty'      => 0,
                ]);
            }
        }

        // variants not present in request => deactivate
        ProductVariant::where('product_id', $product->id)
            ->whereNotIn('id', $seenVariantIds)
            ->update(['status' => 0]);
    }

    /**
     * RESTOCK: add a new serial batch to an existing variant.
     * Use this instead of editing variant stock directly.
     */
    public static function restockVariantWithBatch(
        int $variantId,
        string $serialStart,
        string $serialEnd,
        int $qty,
        ?string $batchNo = null
    ): void {
        if ($qty <= 0) {
            throw new \Exception('Restock qty must be greater than 0.');
        }

        $variant = ProductVariant::findOrFail($variantId);
        if ((int)$variant->track_serial !== 1) {
            throw new \Exception('This variant is not configured for serial tracking.');
        }

        self::assertSerialRangeHasStock($serialStart, $serialEnd, $qty);
        self::ensureNoBatchOverlap($variantId, $serialStart, $serialEnd);

        ProductVariantSerialBatch::create([
            'variant_id'   => $variantId,
            'batch_no'     => $batchNo ?: ('RST-' . $variantId . '-' . now()->format('YmdHis')),
            'serial_start' => $serialStart,
            'serial_end'   => $serialEnd,
            'qty'          => $qty,
            'sold_qty'     => 0,
        ]);
    }

    /**
     * Allocate serials on successful payment (FIFO batches).
     * Stores ONLY sold serials (not all inventory).
     */
    public static function allocateSerialsForOrderItem(int $variantId, int $orderItemId, int $qty): array
    {
        if ($qty <= 0) return [];

        return DB::transaction(function () use ($variantId, $orderItemId, $qty) {
            $variant = ProductVariant::where('id', $variantId)->lockForUpdate()->firstOrFail();
            if ((int)$variant->track_serial !== 1) {
                throw new \Exception('Variant is not serial-tracked.');
            }

            $batches = ProductVariantSerialBatch::where('variant_id', $variantId)
                ->whereRaw('sold_qty < qty')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            $allocated = [];
            $need = $qty;

            foreach ($batches as $batch) {
                if ($need <= 0) break;

                $available = (int)$batch->qty - (int)$batch->sold_qty;
                if ($available <= 0) continue;

                $take = min($available, $need);

                $width = max(strlen((string)$batch->serial_start), strlen((string)$batch->serial_end));
                $firstSerial = self::addNumericString((string)$batch->serial_start, (int)$batch->sold_qty, $width);

                // store sold serials (only sold, not all)
                for ($i = 0; $i < $take; $i++) {
                    $serial = self::addNumericString($firstSerial, $i, $width);

                    ProductVariantSoldSerial::create([
                        'variant_id'     => $variantId,
                        'order_item_id'  => $orderItemId,
                        'serial'         => $serial,
                        'status'         => 'sold',
                    ]);

                    $allocated[] = $serial;
                }

                $batch->sold_qty = (int)$batch->sold_qty + $take;
                $batch->save();

                $need -= $take;
            }

            if ($need > 0) {
                throw new \Exception('Not enough serial stock available.');
            }

            return $allocated;
        });
    }

    /**
     * Calculate available stock from batches (serial-tracked variants).
     */
    public static function getVariantAvailableStock(int $variantId): int
    {
        $sum = ProductVariantSerialBatch::where('variant_id', $variantId)
            ->selectRaw('COALESCE(SUM(qty - sold_qty),0) as available')
            ->value('available');

        return (int)$sum;
    }

    /**
     * Suggest next serial start based on the last serial end.
     */
    public static function suggestNextSerialStart(string $serialEnd): ?string
    {
        if ($serialEnd === '' || !preg_match('/^\d+$/', $serialEnd)) {
            return null;
        }

        $width = strlen($serialEnd);
        return self::addNumericString($serialEnd, 1, $width);
    }

    /**
     * Return serial (basic).
     * You can expand it with a separate return log table if needed.
     */
    public static function markSerialReturned(int $variantId, int $orderItemId, string $serial): void
    {
        $row = ProductVariantSoldSerial::where('variant_id', $variantId)
            ->where('order_item_id', $orderItemId)
            ->where('serial', $serial)
            ->lockForUpdate()
            ->first();

        if (!$row) {
            throw new \Exception('Serial not found for this order item.');
        }

        $row->status = 'returned';
        $row->save();
    }

    /**
     * Prevent overlapping serial ranges per variant.
     */
    private static function ensureNoBatchOverlap(int $variantId, string $newStart, string $newEnd): void
    {
        // numeric-only ranges
        if (!preg_match('/^\d+$/', $newStart) || !preg_match('/^\d+$/', $newEnd)) {
            throw new \Exception('Serial start/end must be numeric.');
        }

        $overlap = ProductVariantSerialBatch::where('variant_id', $variantId)
            ->where(function ($q) use ($newStart, $newEnd) {
                // (new_start <= old_end) AND (new_end >= old_start)
                $q->whereRaw('? <= serial_end', [$newStart])
                  ->whereRaw('? >= serial_start', [$newEnd]);
            })
            ->exists();

        if ($overlap) {
            throw new \Exception('Serial range overlaps with existing batch for this variant.');
        }
    }

    private static function variantSignatureFromDb(int $variantId): ?string
    {
        $pairs = ProductVariantValue::query()
            ->join('product_option_values', 'product_option_values.id', '=', 'product_variant_values.option_value_id')
            ->join('product_options', 'product_options.id', '=', 'product_option_values.product_option_id')
            ->where('product_variant_values.variant_id', $variantId)
            ->get([
                'product_options.name as option_name',
                'product_option_values.value as option_value',
            ]);

        if ($pairs->isEmpty()) {
            return '';
        }

        $parts = $pairs
            ->map(function ($pair) {
                return trim((string)$pair->option_name) . '::' . trim((string)$pair->option_value);
            })
            ->sort()
            ->values()
            ->all();

        return implode('|', $parts);
    }

    private static function variantSignatureFromMap(array $map): string
    {
        if (empty($map)) {
            return '';
        }

        $normalized = [];
        foreach ($map as $optName => $optValue) {
            $name = trim((string)$optName);
            $value = trim((string)$optValue);

            if ($name === '' || $value === '') {
                continue;
            }

            $normalized[$name] = $value;
        }

        if (empty($normalized)) {
            return '';
        }

        ksort($normalized);

        $parts = [];
        foreach ($normalized as $name => $value) {
            $parts[] = $name . '::' . $value;
        }

        return implode('|', $parts);
    }

    private static function normalizeMetaKeywords($metaKeywords): ?string
    {
        $keywords = [];

        if (is_array($metaKeywords)) {
            $keywords = $metaKeywords;
        } elseif (is_string($metaKeywords)) {
            $keywords = explode(',', $metaKeywords);
        }

        $keywords = array_values(array_filter(array_map(function ($keyword) {
            return trim((string)$keyword);
        }, $keywords), function ($keyword) {
            return $keyword !== '';
        }));

        return !empty($keywords) ? json_encode($keywords, \JSON_UNESCAPED_UNICODE) : null;
    }

    private static function assertSerialRangeHasStock(string $start, string $end, int $count): void
    {
        if ($count <= 0) return;

        if (!preg_match('/^\d+$/', $start) || !preg_match('/^\d+$/', $end)) {
            throw new \Exception('Serial start/end must be numeric.');
        }

        if (self::compareNumericStrings($start, $end) > 0) {
            throw new \Exception('Serial end must be greater than or equal to start.');
        }

        $width = max(strlen($start), strlen($end));
        $last = self::addNumericString($start, $count - 1, $width);

        if (self::compareNumericStrings($last, $end) > 0) {
            throw new \Exception('Variants serial range is smaller than stock.');
        }
    }

    private static function compareNumericStrings(string $a, string $b): int
    {
        $aTrim = ltrim($a, '0');
        $bTrim = ltrim($b, '0');
        $aNorm = $aTrim === '' ? '0' : $aTrim;
        $bNorm = $bTrim === '' ? '0' : $bTrim;

        if (strlen($aNorm) > strlen($bNorm)) return 1;
        if (strlen($aNorm) < strlen($bNorm)) return -1;
        return strcmp($aNorm, $bNorm);
    }

    private static function addNumericString(string $base, int $offset, int $width): string
    {
        $offsetStr = (string)max(0, $offset);
        $sum = self::addNumericStrings($base, $offsetStr);
        return str_pad($sum, $width, '0', \STR_PAD_LEFT);
    }

    private static function addNumericStrings(string $a, string $b): string
    {
        $a = ltrim($a, '0');
        $b = ltrim($b, '0');
        $a = $a === '' ? '0' : $a;
        $b = $b === '' ? '0' : $b;

        $i = strlen($a) - 1;
        $j = strlen($b) - 1;
        $carry = 0;
        $result = '';

        while ($i >= 0 || $j >= 0 || $carry > 0) {
            $digit = $carry;
            if ($i >= 0) {
                $digit += (int)$a[$i];
                $i--;
            }
            if ($j >= 0) {
                $digit += (int)$b[$j];
                $j--;
            }
            $carry = intdiv($digit, 10);
            $result = (string)($digit % 10) . $result;
        }

        $out = ltrim($result, '0');
        return $out === '' ? '0' : $out;
    }
}
