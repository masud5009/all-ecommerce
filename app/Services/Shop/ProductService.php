<?php

namespace App\Services\Shop;

use App\Http\Helpers\ImageUpload;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            //download file
            if (strtolower((string)$request->type) === 'digital') {
                if ($request->file_type === 'upload' && $request->hasFile('download_file')) {
                    $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
                }
            }

            $product = new Product();
            $product->thumbnail = $thumbnail;

            //if variants enabled, product stock becomes 0 (inventory is variant-level)
            $product->has_variants = $hasVariants ? 1 : 0;
            $product->stock = $hasVariants ? 0 : (int)($request->stock ?? 0);
            $product->last_restock_qty = $hasVariants ? 0 : (int)($request->stock ?? 0);

            // base sku optional if variants
            $product->sku = $hasVariants ? ($request->sku ?? null) : $request->sku;

            //when product has_variants, current_price won't come in request
            $product->current_price = $request->current_price ?? 0;
            $product->previous_price = $request->previous_price;

            $product->type = $request->type;
            $product->file_type = $request->file_type;
            $product->download_link = $request->download_link;
            $product->download_file = $download_file ?? null;

            $product->status = $request->status;
            $product->save();

            //store slider image
            $sliders = $request->slider_image;
            if ($sliders) {
                $pis = SliderImage::findOrFail($sliders);
                foreach ($pis as $pi) {
                    $pi->item_id = $product->id;
                    $pi->save();
                }
            }

            //store language contents
            $languages = app('languages');
            foreach ($languages as $language) {
                $code = $language->code;

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

                    $content = new ProductContent();
                    $content->language_id = $language->id;
                    $content->product_id = $product->id;
                    $content->category_id = $request->input($code . '_category_id');
                    $content->title = $request->input($code . '_title');
                    $content->slug = createSlug($request->input($code . '_title'));
                    $content->summary = $request->input($code . '_summary');
                    $content->description = $request->input($code . '_description');
                    $content->meta_keyword = (is_array($metaKeywords) && count(array_filter($metaKeywords)))
                        ? json_encode(array_values(array_filter($metaKeywords)))
                        : null;
                    $content->meta_description = $request->input($code . '_meta_description');
                    $content->save();
                }
            }

            //store variants/options
            self::storeVariantsForProduct($product, $request);

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return false;
        }
    }

    public static function update(Request $request, $id)
    {
        $hasVariants = $request->boolean('has_variants');

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            //thumbnail
            $thumbnail = $product->thumbnail;
            if ($request->hasFile('thumbnail')) {
                $thumbnail = ImageUpload::update(public_path('assets/img/product/'), $request->file('thumbnail'), $product->thumbnail);
            }

            //download file
            $download_file = $product->download_file;
            if (strtolower((string)$product->type) === 'digital') {
                if ($request->file_type === 'upload' && $request->hasFile('download_file')) {
                    $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
                }
            }

            $product->thumbnail = $thumbnail;

            //if variants enabled, product stock becomes 0 (inventory is variant-level)
            $product->has_variants = $hasVariants ? 1 : 0;
            $product->stock = $hasVariants ? 0 : (int)($request->stock ?? 0);
            $product->last_restock_qty = $hasVariants ? 0 : (int)($request->stock ?? 0);

            // base sku optional if variants
            $product->sku = $hasVariants ? ($request->sku ?? null) : $request->sku;

            //when product has_variants, current_price won't come in request
            $product->current_price = $request->current_price ?? $product->current_price;
            $product->previous_price = $request->previous_price;

            $product->type = $request->type;
            $product->file_type = $request->file_type;
            $product->download_link = $request->download_link;
            $product->download_file = ($request->file_type == 'upload') ? $download_file : null;
            $product->status = $request->status;
            $product->save();

            //store slider image
            $sliders = $request->slider_image;
            if ($sliders) {
                $pis = SliderImage::findOrFail($sliders);
                foreach ($pis as $pi) {
                    $pi->item_id = $product->id;
                    $pi->save();
                }
            }

            //update language contents
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
                    $content->title = $request->input($code . '_title');
                    $content->slug = createSlug($request->input($code . '_title'));
                    $content->summary = $request->input($code . '_summary');
                    $content->description = $request->input($code . '_description');
                    $content->meta_keyword = (is_array($metaKeywords) && count(array_filter($metaKeywords)))
                        ? json_encode(array_values(array_filter($metaKeywords)))
                        : null;
                    $content->meta_description = $request->input($code . '_meta_description');
                    $content->save();
                }
            }

            //update variants/options
            self::storeVariantsForProduct($product, $request);

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return false;
        }
    }

    public static function storeVariantsForProduct(Product $product, Request $request)
    {
        $hasVariants = $request->boolean('has_variants');

        if (!$hasVariants) {
            // if product has no variants, clear any previous variant data
            ProductVariantValue::whereIn('variant_id', ProductVariant::where('product_id', $product->id)->pluck('id'))->delete();
            ProductVariant::where('product_id', $product->id)->delete();

            ProductOptionValue::whereIn('product_option_id', ProductOption::where('product_id', $product->id)->pluck('id'))->delete();
            ProductOption::where('product_id', $product->id)->delete();
            return;
        }

        //must have variants data
        $optionsInput  = $request->input('variant_options', []);
        $variantsInput = $request->input('variants', []);

        if (!is_array($optionsInput) || count($optionsInput) < 1) {
            throw new \Exception('Variant options are required.');
        }
        if (!is_array($variantsInput) || count($variantsInput) < 1) {
            throw new \Exception('Variants are required. Please generate variants first.');
        }

        //delete existing first
        ProductVariantValue::whereIn('variant_id', ProductVariant::where('product_id', $product->id)->pluck('id'))->delete();
        ProductVariant::where('product_id', $product->id)->delete();

        ProductOptionValue::whereIn('product_option_id', ProductOption::where('product_id', $product->id)->pluck('id'))->delete();
        ProductOption::where('product_id', $product->id)->delete();

        //create options + values and build a lookup map
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

        //create variants + pivot mapping
        foreach ($variantsInput as $v) {
            $sku    = $v['sku'] ?? null;
            $price  = $v['price'] ?? null;
            $stock  = (int)($v['stock'] ?? 0);
            $status = (int)($v['status'] ?? 1);

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku'        => ($sku === '' ? null : $sku),
                'price'      => ($price === '' || $price === null) ? null : (float)$price,
                'stock'      => $stock,
                'status'     => $status,
            ]);

            $map = json_decode($v['map'] ?? '{}', true) ?: [];

            foreach ($map as $optName => $optValue) {
                $optionValueId = $valueIdMap[$optName][$optValue] ?? null;
                if ($optionValueId) {
                    ProductVariantValue::create([
                        'variant_id'      => $variant->id,
                        'option_value_id' => $optionValueId,
                    ]);
                }
            }
        }
    }
}
