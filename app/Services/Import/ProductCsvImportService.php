<?php

namespace App\Services\Import;

use App\Models\Admin\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductContent;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCsvImportService
{
    protected ProductImportValidator $validator;
    protected $defaultLanguage;
    protected array $categoryNameMap = [];
    protected array $categoryIdSet = [];
    protected array $existingProductSkuMap = [];
    protected array $existingVariantSkuMap = [];
    protected array $seenProductSkus = [];
    protected array $seenVariantSkus = [];

    public function __construct(?ProductImportValidator $validator = null)
    {
        $this->validator = $validator ?? new ProductImportValidator();
        $this->defaultLanguage = Language::where('is_default', 1)->first();

        $categories = ProductCategory::where('language_id', $this->defaultLanguage->id)->get();
        $this->categoryNameMap = $categories
            ->mapWithKeys(fn ($c) => [mb_strtolower(trim((string) $c->name)) => $c->id])
            ->toArray();
        $this->categoryIdSet = $categories->pluck('id')->all();

        $this->existingProductSkuMap = Product::whereNotNull('sku')
            ->pluck('id', 'sku')
            ->mapWithKeys(fn ($id, $sku) => [mb_strtolower(trim((string) $sku)) => $id])
            ->toArray();

        $this->existingVariantSkuMap = ProductVariant::whereNotNull('sku')
            ->pluck('product_id', 'sku')
            ->mapWithKeys(fn ($productId, $sku) => [mb_strtolower(trim((string) $sku)) => $productId])
            ->toArray();
    }

    public function import(Collection $rows): array
    {
        $result = [
            'inserted' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => [],
        ];

        $groups = [];

        foreach ($rows as $index => $row) {
            $rowNum = $index + 2; // heading row is 1
            $data = $this->normalizeRow($row->toArray());
            $data = $this->normalizeControlFields($data);

            if (empty($data['row_type'])) {
                $this->addError($result, $rowNum, 'row_type is required.');
                continue;
            }

            $rowType = (string)$data['row_type'];

            if ($rowType === 'product') {
                if (empty($data['product_sku']) && !empty($data['sku'])) {
                    $data['product_sku'] = $data['sku'];
                }

                $errors = $this->validator->validateProductRow($data);
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        $this->addError($result, $rowNum, $error);
                    }
                    continue;
                }

                $groupKey = trim((string)($data['group_key'] ?? ''));
                if ($groupKey === '') {
                    $groupKey = "__row_{$rowNum}";
                }

                if (isset($groups[$groupKey]['product'])) {
                    $this->addError($result, $rowNum, "Duplicate product row for group_key {$groupKey}.");
                    continue;
                }

                $groups[$groupKey]['product'] = ['rowNum' => $rowNum, 'data' => $data];
                $groups[$groupKey]['variants'] = $groups[$groupKey]['variants'] ?? [];
            } elseif ($rowType === 'variant') {
                $errors = $this->validator->validateVariantRow($data);
                if (!empty($errors)) {
                    foreach ($errors as $error) {
                        $this->addError($result, $rowNum, $error);
                    }
                    continue;
                }

                $groupKey = trim((string)($data['group_key'] ?? ''));
                $groups[$groupKey]['variants'][] = ['rowNum' => $rowNum, 'data' => $data];
            } else {
                $this->addError($result, $rowNum, 'row_type must be product or variant.');
            }
        }

        foreach ($groups as $groupKey => $group) {
            if (empty($group['product'])) {
                foreach ($group['variants'] ?? [] as $variantRow) {
                    $this->addError($result, $variantRow['rowNum'], "group_key {$groupKey} has no product row.");
                }
                continue;
            }

            $productRow = $group['product'];
            $variantRows = $group['variants'] ?? [];

            $groupSuccess = $this->processGroup($groupKey, $productRow, $variantRows, $result);
            if (!$groupSuccess) {
                foreach ($variantRows as $variantRow) {
                    $this->addError($result, $variantRow['rowNum'], "group_key {$groupKey} skipped due to product/variant errors.");
                }
            }
        }

        return $result;
    }

    protected function processGroup(string $groupKey, array $productRow, array $variantRows, array &$result): bool
    {
        $rowNum = $productRow['rowNum'];
        $data = $productRow['data'];

        $productSku = trim((string)($data['product_sku'] ?? ''));
        $productSkuKey = mb_strtolower($productSku);

        if ($productSku === '') {
            $this->addError($result, $rowNum, 'product_sku is required.');
            return false;
        }

        if (isset($this->seenProductSkus[$productSkuKey])) {
            $this->addError($result, $rowNum, 'Duplicate product_sku in file.');
            return false;
        }
        $this->seenProductSkus[$productSkuKey] = true;

        $productId = $this->existingProductSkuMap[$productSkuKey] ?? null;
        $product = $productId ? Product::find($productId) : null;
        $isNew = $product === null;

        $typeLower = mb_strtolower((string)$data['type']);
        $type = $typeLower === 'digital' ? 'Digital' : 'Physical';

        $status = $this->normalizeStatus($data['status'] ?? null);
        if ($status === null) {
            $status = $product?->status ?? 1;
        }

        $categoryId = $this->resolveCategoryId($data, $product);
        if (!$categoryId) {
            $this->addError($result, $rowNum, 'Category not found. Provide valid category_id or category_name.');
            return false;
        }

        $currentPrice = $this->normalizeNumber($data['current_price'] ?? null);
        if ($currentPrice === null && $this->stringOrEmpty($data['current_price'] ?? null) !== '') {
            $this->addError($result, $rowNum, 'current_price must be numeric.');
            return false;
        }

        $previousPrice = $this->normalizeNumber($data['previous_price'] ?? null);
        if ($previousPrice === null && $this->stringOrEmpty($data['previous_price'] ?? null) !== '') {
            $this->addError($result, $rowNum, 'previous_price must be numeric.');
            return false;
        }

        $downloadLink = $this->stringOrNull($data['download_link'] ?? null);
        $variantDownloadLink = $this->findVariantDownloadLink($variantRows);
        $existingDownloadLink = $product?->download_link;
        $finalDownloadLink = $downloadLink ?: ($existingDownloadLink ?: $variantDownloadLink);

        if ($typeLower === 'digital' && $finalDownloadLink === null) {
            $this->addError($result, $rowNum, 'download_link is required for digital products (product or variant row).');
            return false;
        }

        $summary = $this->stringOrNull($data['summary'] ?? null);
        $metaKeywordRaw = $this->stringOrEmpty($data['meta_keyword'] ?? ($data['meta_keywords'] ?? null));
        $metaKeywords = $this->normalizeKeywords($metaKeywordRaw);
        $metaDescription = $this->stringOrNull($data['meta_description'] ?? null);

        $title = $this->stringOrEmpty($data['title'] ?? null);
        $description = $this->stringOrEmpty($data['description'] ?? null);

        if ($title === '' || $description === '') {
            $this->addError($result, $rowNum, 'title and description are required.');
            return false;
        }

        $variantPayload = null;
        if (count($variantRows) > 0) {
            $variantPayload = $this->prepareVariants($variantRows, $groupKey, $product?->id, $result);
            if ($variantPayload === null) {
                return false;
            }
        }

        DB::beginTransaction();
        try {
            if ($isNew) {
                $product = new Product();
                $product->thumbnail = 'noimage.jpg';
            }

            $product->sku = $productSku;
            $product->type = $type;
            $product->status = $status;
            $product->current_price = $currentPrice ?? ($product->current_price ?? 0);
            $product->previous_price = $previousPrice ?? $product->previous_price;
            $product->file_type = $typeLower === 'digital' ? 'link' : null;
            $product->download_link = $typeLower === 'digital' ? $finalDownloadLink : null;
            $product->download_file = $typeLower === 'digital' ? $product->download_file : null;

            if ($variantPayload !== null) {
                $product->has_variants = 1;
                $product->stock = 0;
                $product->last_restock_qty = 0;
            } elseif ($isNew) {
                $product->has_variants = 0;
                if ($typeLower === 'physical') {
                    $stock = $this->normalizeInteger($data['stock'] ?? 0);
                    $product->stock = $stock ?? 0;
                    $product->last_restock_qty = $stock ?? 0;
                } else {
                    $product->stock = 0;
                    $product->last_restock_qty = 0;
                }
            } else {
                if ($product->has_variants) {
                    $product->stock = 0;
                } elseif ($typeLower === 'physical' && $this->stringOrEmpty($data['stock'] ?? null) !== '') {
                    $stock = $this->normalizeInteger($data['stock'] ?? 0);
                    $product->stock = $stock ?? $product->stock;
                    $product->last_restock_qty = $stock ?? $product->last_restock_qty;
                }
            }

            $product->save();

            $content = ProductContent::where('product_id', $product->id)
                ->where('language_id', $this->defaultLanguage->id)
                ->first();

            if (!$content) {
                $content = new ProductContent();
                $content->language_id = $this->defaultLanguage->id;
                $content->product_id = $product->id;
            }

            $content->category_id = $categoryId;
            $content->title = $title;
            $content->slug = createSlug($title);
            $content->summary = $summary;
            $content->description = $description;
            $content->meta_keyword = $metaKeywords;
            $content->meta_description = $metaDescription;
            $content->save();

            if ($variantPayload !== null) {
                $this->replaceVariants($product, $variantPayload);
            }

            DB::commit();

            if ($isNew) {
                $result['inserted']++;
            } else {
                $result['updated']++;
            }

            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->addError($result, $rowNum, "Failed to import group {$groupKey}.");
            return false;
        }
    }

    protected function prepareVariants(array $variantRows, string $groupKey, ?int $productId, array &$result): ?array
    {
        $optionOrder = [];
        $optionValues = [];
        $parsedRows = [];

        foreach ($variantRows as $row) {
            $rowNum = $row['rowNum'];
            $data = $row['data'];
            $map = $this->validator->parseVariantMap((string)($data['variant_map'] ?? ''));

            if ($map === null) {
                $this->addError($result, $rowNum, "variant_map format must be Key=Value|Key=Value.");
                continue;
            }

            foreach ($map as $name => $value) {
                if (!isset($optionOrder[$name])) {
                    $optionOrder[$name] = count($optionOrder);
                }
                $optionValues[$name] = $optionValues[$name] ?? [];
                if (!isset($optionValues[$name][$value])) {
                    $optionValues[$name][$value] = count($optionValues[$name]);
                }
            }

            $parsedRows[] = [
                'rowNum' => $rowNum,
                'data' => $data,
                'map' => $map,
            ];
        }

        if (count($parsedRows) === 0) {
            $this->addError($result, $variantRows[0]['rowNum'], "group_key {$groupKey} has no valid variants.");
            return null;
        }

        $optionNames = array_keys($optionOrder);
        $validVariants = [];

        foreach ($parsedRows as $row) {
            $rowNum = $row['rowNum'];
            $map = $row['map'];

            foreach ($optionNames as $name) {
                if (!array_key_exists($name, $map)) {
                    $this->addError($result, $rowNum, "variant_map missing option {$name}.");
                    continue 2;
                }
            }

            $variantSku = $this->stringOrNull($row['data']['variant_sku'] ?? null);
            if ($variantSku === null) {
                $this->addError($result, $rowNum, 'variant_sku is required.');
                continue;
            }

            $variantSkuKey = mb_strtolower($variantSku);
            if (isset($this->seenVariantSkus[$variantSkuKey])) {
                $this->addError($result, $rowNum, 'Duplicate variant_sku in file.');
                continue;
            }

            $existingVariantProductId = $this->existingVariantSkuMap[$variantSkuKey] ?? null;
            if ($existingVariantProductId !== null && $existingVariantProductId !== $productId) {
                $this->addError($result, $rowNum, 'variant_sku already exists for another product.');
                continue;
            }

            $variantPrice = $this->normalizeNumber($row['data']['variant_price'] ?? null);
            if ($variantPrice === null && $this->stringOrEmpty($row['data']['variant_price'] ?? null) !== '') {
                $this->addError($result, $rowNum, 'variant_price must be numeric.');
                continue;
            }

            $variantStock = $this->normalizeInteger($row['data']['variant_stock'] ?? null);
            if ($variantStock === null || $variantStock < 0) {
                $this->addError($result, $rowNum, 'variant_stock must be a non-negative integer.');
                continue;
            }

            $variantStatus = $this->normalizeStatus($row['data']['variant_status'] ?? null);
            if ($variantStatus === null) {
                $this->addError($result, $rowNum, 'variant_status must be 1/0 or active/inactive.');
                continue;
            }

            $this->seenVariantSkus[$variantSkuKey] = true;

            $validVariants[] = [
                'rowNum' => $rowNum,
                'map' => $map,
                'sku' => $variantSku,
                'price' => $variantPrice,
                'stock' => $variantStock,
                'status' => $variantStatus,
            ];
        }

        if (count($validVariants) === 0) {
            $this->addError($result, $variantRows[0]['rowNum'], "group_key {$groupKey} has no valid variants.");
            return null;
        }

        return [
            'option_order' => $optionOrder,
            'option_values' => $optionValues,
            'variants' => $validVariants,
        ];
    }

    protected function replaceVariants(Product $product, array $payload): void
    {
        $variantIds = ProductVariant::where('product_id', $product->id)->pluck('id');
        if ($variantIds->isNotEmpty()) {
            ProductVariantValue::whereIn('variant_id', $variantIds)->delete();
            \App\Models\ProductVariantSerialBatch::whereIn('variant_id', $variantIds)->delete();
            \App\Models\ProductVariantSoldSerial::whereIn('variant_id', $variantIds)->delete();
        }
        ProductVariant::where('product_id', $product->id)->delete();

        $optionIds = ProductOption::where('product_id', $product->id)->pluck('id');
        if ($optionIds->isNotEmpty()) {
            ProductOptionValue::whereIn('product_option_id', $optionIds)->delete();
        }
        ProductOption::where('product_id', $product->id)->delete();

        $valueIdMap = [];
        foreach ($payload['option_order'] as $optionName => $position) {
            $option = ProductOption::create([
                'product_id' => $product->id,
                'name' => $optionName,
                'position' => $position,
            ]);

            foreach ($payload['option_values'][$optionName] as $value => $pos) {
                $optionValue = ProductOptionValue::create([
                    'product_option_id' => $option->id,
                    'value' => $value,
                    'position' => $pos,
                ]);
                $valueIdMap[$optionName][$value] = $optionValue->id;
            }
        }

        foreach ($payload['variants'] as $variantRow) {
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantRow['sku'],
                'price' => $variantRow['price'],
                'stock' => $variantRow['stock'],
                'status' => $variantRow['status'],
            ]);

            foreach ($variantRow['map'] as $optionName => $optionValue) {
                $optionValueId = $valueIdMap[$optionName][$optionValue] ?? null;
                if ($optionValueId) {
                    ProductVariantValue::create([
                        'variant_id' => $variant->id,
                        'option_value_id' => $optionValueId,
                    ]);
                }
            }
        }
    }

    protected function resolveCategoryId(array $data, ?Product $product): ?int
    {
        $categoryIdRaw = $this->stringOrEmpty($data['category_id'] ?? null);
        if ($categoryIdRaw !== '') {
            $categoryId = (int) $categoryIdRaw;
            return in_array($categoryId, $this->categoryIdSet, true) ? $categoryId : null;
        }

        $categoryName = $this->stringOrEmpty($data['category_name'] ?? null);
        if ($categoryName !== '') {
            $key = mb_strtolower($categoryName);
            return $this->categoryNameMap[$key] ?? null;
        }

        if ($product) {
            $content = ProductContent::where('product_id', $product->id)
                ->where('language_id', $this->defaultLanguage->id)
                ->first();
            return $content?->category_id;
        }

        return null;
    }

    protected function findVariantDownloadLink(array $variantRows): ?string
    {
        foreach ($variantRows as $row) {
            $link = $this->stringOrNull($row['data']['download_link'] ?? null);
            if ($link !== null) {
                return $link;
            }
        }
        return null;
    }

    protected function normalizeStatus($value): ?int
    {
        $raw = mb_strtolower($this->stringOrEmpty($value));
        if ($raw === '' || $raw === '1' || $raw === 'active') {
            return 1;
        }
        if ($raw === '0' || $raw === 'inactive') {
            return 0;
        }
        return null;
    }

    protected function normalizeNumber($value): ?float
    {
        $raw = $this->stringOrEmpty($value);
        if ($raw === '') {
            return null;
        }
        if (!is_numeric($raw)) {
            return null;
        }
        return (float) $raw;
    }

    protected function normalizeInteger($value): ?int
    {
        $raw = $this->stringOrEmpty($value);
        if ($raw === '') {
            return null;
        }
        if (!is_numeric($raw)) {
            return null;
        }
        return (int) $raw;
    }

    protected function normalizeRow(array $row): array
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normalizedKey = Str::snake(trim((string)$key));
            $normalized[$normalizedKey] = is_string($value) ? trim($value) : $value;
        }
        return $normalized;
    }

    protected function normalizeControlFields(array $data): array
    {
        foreach (['row_type', 'type', 'status', 'variant_status'] as $field) {
            if (array_key_exists($field, $data)) {
                $data[$field] = mb_strtolower(trim((string)$data[$field]));
            }
        }
        return $data;
    }

    protected function stringOrEmpty($value): string
    {
        if ($value === null) {
            return '';
        }
        return trim((string)$value);
    }

    protected function stringOrNull($value): ?string
    {
        $value = $this->stringOrEmpty($value);
        return $value === '' ? null : $value;
    }

    protected function normalizeKeywords(string $value): ?string
    {
        if ($value === '') {
            return null;
        }
        $parts = array_filter(array_map('trim', explode(',', $value)));
        return count($parts) ? json_encode(array_values($parts)) : null;
    }

    protected function addError(array &$result, int $rowNum, string $message): void
    {
        $result['errors'][] = "Row {$rowNum}: {$message}";
        $result['skipped']++;
    }
}
