<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ImageUpload;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Models\Admin\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductContent;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

// ✅ Variations models (ensure these exist)
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $language_id = Language::where('code', $request->language)->pluck('id');

        $data['products'] = Product::join('product_contents', 'product_contents.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
            ->where('product_contents.language_id', $language_id)
            ->where('product_categories.language_id', $language_id)
            ->select('products.id', 'products.thumbnail', 'products.status', 'product_contents.title', 'product_categories.name as categoryName')
            ->orderBy('products.created_at', 'desc')
            ->get();

        return view('admin.product.index', $data);
    }

    public function create(Request $request)
    {
        $default_language = app('defaultLang');

        // UI sends 'physical'/'digital' lowercase
        if ($request->type != 'physical' && $request->type != 'digital') {
            return redirect()->route('admin.product', ['language' => $default_language->code]);
        }

        $languages = app('languages');
        foreach ($languages as $language) {
            $language->categories = ProductCategory::where([['status', 1], ['language_id', $language->id]])
                ->orderBy('serial_number', 'desc')
                ->get();
        }
        $data['languages'] = $languages;

        return view('admin.product.create', $data);
    }

    /**
     * ✅ helper: save variants/options (replace mode)
     */
    private function storeVariantsForProduct(Product $product, Request $request): void
    {
        $hasVariants = $request->boolean('has_variants');

        if (!$hasVariants) {
            // if product has no variants, clear any previous variant data (safety)
            ProductVariantValue::whereIn('variant_id', ProductVariant::where('product_id', $product->id)->pluck('id'))->delete();
            ProductVariant::where('product_id', $product->id)->delete();

            ProductOptionValue::whereIn('product_option_id', ProductOption::where('product_id', $product->id)->pluck('id'))->delete();
            ProductOption::where('product_id', $product->id)->delete();
            return;
        }

        // ✅ must have variants data
        $optionsInput  = $request->input('variant_options', []);
        $variantsInput = $request->input('variants', []);

        if (!is_array($optionsInput) || count($optionsInput) < 1) {
            throw new \Exception('Variant options are required.');
        }
        if (!is_array($variantsInput) || count($variantsInput) < 1) {
            throw new \Exception('Variants are required. Please generate variants first.');
        }

        // ✅ replace mode: delete existing first
        ProductVariantValue::whereIn('variant_id', ProductVariant::where('product_id', $product->id)->pluck('id'))->delete();
        ProductVariant::where('product_id', $product->id)->delete();

        ProductOptionValue::whereIn('product_option_id', ProductOption::where('product_id', $product->id)->pluck('id'))->delete();
        ProductOption::where('product_id', $product->id)->delete();

        // ✅ create options + values and build a lookup map
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

        // ✅ create variants + pivot mapping
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

    public function store(StoreRequest $request)
    {
        $hasVariants = $request->boolean('has_variants');

        DB::beginTransaction();
        try {
            // ✅ safe defaults
            $thumbnail = null;
            $download_file = null;

            if ($request->hasFile('thumbnail')) {
                $thumbnail = ImageUpload::store(public_path('assets/img/product/'), $request->file('thumbnail'));
            }

            // UI sends 'digital' not 'Digital'
            if (strtolower((string)$request->type) === 'digital') {
                if ($request->file_type === 'upload' && $request->hasFile('download_file')) {
                    $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
                }
            }

            $product = new Product();
            $product->has_variants = $hasVariants ? 1 : 0;

            // ✅ if variants enabled, product stock becomes 0 (inventory is variant-level)
            $product->stock = $hasVariants ? 0 : (int)($request->stock ?? 0);
            $product->last_restock_qty = $hasVariants ? 0 : (int)($request->stock ?? 0);

            // base sku optional if variants
            $product->sku = $hasVariants ? ($request->sku ?? null) : $request->sku;

            $product->thumbnail = $thumbnail;
            // ⚠️ if your UI disables current_price when has_variants, it won't come in request.
            // keep fallback 0 or you can keep it editable (recommended).
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

            // ✅ store language contents
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
                    $content = new ProductContent();
                    $content->language_id = $language->id;
                    $content->product_id = $product->id;
                    $content->category_id = $request->input($code . '_category_id');
                    $content->title = $request->input($code . '_title');
                    $content->slug = createSlug($request->input($code . '_title'));
                    $content->summary = $request->input($code . '_summary');
                    $content->description = $request->input($code . '_description');
                    $content->meta_keyword = json_encode($request->input($code . '_meta_keyword'));
                    $content->meta_description = $request->input($code . '_meta_description');
                    $content->save();
                }
            }

            // ✅ store variants/options (new)
            $this->storeVariantsForProduct($product, $request);

            DB::commit();
            Session::flash('success', __('Product created successfully'));
            return 'success';
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $languages = app('languages');

        $product = Product::with('sliderImage')->findOrFail($id);
        $languages->map(function ($language) use ($product) {
            $language->content = $product->content->where('language_id', $language->id)->first();
            $language->categories = ProductCategory::where([['status', 1], ['language_id', $language->id]])
                ->orderBy('serial_number', 'desc')
                ->get();
            $language->is_added = $product->content->where('language_id', $language->id)->isNotEmpty();
        });
        $data['languages'] = $languages;
        $data['product'] = $product;

        return view('admin.product.edit', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $hasVariants = $request->boolean('has_variants');

        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);

            // ✅ thumbnail
            $thumbnail = $product->thumbnail;
            if ($request->hasFile('thumbnail')) {
                $thumbnail = ImageUpload::update(public_path('assets/img/product/'), $request->file('thumbnail'), $product->thumbnail);
            }

            // ✅ download file (fix case)
            $download_file = $product->download_file;
            if (strtolower((string)$product->type) === 'digital') {
                if ($request->file_type === 'upload' && $request->hasFile('download_file')) {
                    $download_file = ImageUpload::store(public_path('assets/img/product/file/'), $request->file('download_file'));
                }
            }

            $product->has_variants = $hasVariants ? 1 : 0;

            $product->stock = $hasVariants ? 0 : (int)($request->stock ?? 0);
            $product->last_restock_qty = $hasVariants ? 0 : (int)($request->stock ?? 0);

            $product->sku = $hasVariants ? ($request->sku ?? null) : $request->sku;

            $product->thumbnail = $thumbnail;
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

            // ✅ update language contents
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
                    $content->language_id = $language->id;
                    $content->product_id = $product->id;
                    $content->category_id = $request->input($code . '_category_id');
                    $content->title = $request->input($code . '_title');
                    $content->slug = createSlug($request->input($code . '_title'));
                    $content->summary = $request->input($code . '_summary');
                    $content->description = $request->input($code . '_description');
                    $content->meta_keyword = json_encode($request->input($code . '_meta_keyword'));
                    $content->meta_description = $request->input($code . '_meta_description');
                    $content->save();
                }
            }

            // ✅ update variants/options (replace mode)
            $this->storeVariantsForProduct($product, $request);

            DB::commit();
            Session::flash('success', __('Product update successfully'));
            return 'success';
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //slider image store
    public function imagesstore(Request $request)
    {
        $directory = public_path('assets/img/product/gallery/');
        $filename =  ImageUpload::sliderStore($request->file('file'), $request->all(), $directory);

        $pi = new SliderImage();
        $pi->item_id = $request->item_id;
        $pi->item_type = 'product';
        $pi->image = $filename;
        $pi->save();
        return response()->json(['status' => 'success', 'file_id' => $pi->id]);
    }

    //slider image delete from create form
    public function imageremove(Request $request)
    {
        $pi = SliderImage::findOrFail($request->value);
        @unlink(public_path('assets/img/product/gallery/') . $pi->image);
        $pi->delete();
        return $pi->id;
    }

    //slider image delete from edit form
    public function dbSliderRemove(Request $request)
    {
        $pi = SliderImage::findOrFail($request->fileid);
        $image_count = SliderImage::where('item_id', $pi->item_id)->count();

        if ($image_count > 1) {
            @unlink(public_path('assets/img/product/gallery/') . $pi->image);
            $pi->delete();
            return 'success';
        }
        return 'warning';
    }

    public function changeStatus(Request $request)
    {
        Product::where('id', $request->id)->update(['status' => $request->status]);
        return 'success';
    }
}
