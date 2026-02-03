<?php

namespace App\Http\Controllers\Admin\Product;

use App\Models\Product;
use App\Models\SliderImage;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Models\Admin\Language;
use App\Models\ProductContent;
use App\Models\ProductSetting;
use App\Models\ProductVariant;
use App\Models\ProductCategory;
use App\Http\Helpers\ImageUpload;
use App\Models\ProductOptionValue;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Services\Shop\ProductService;

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

        $data['product_setting'] = ProductSetting::first();

        return view('admin.product.index', $data);
    }

    public function create(Request $request)
    {
        $default_language = app('defaultLang');

        $productSetting = ProductSetting::first();
        $type = request('type');

        /* both disabled */
        if (!$productSetting->physical_product && !$productSetting->digital_product) {
            return redirect()->back();
        }

        /* type-wise restriction */
        if (($type === 'physical' && !$productSetting->physical_product) || ($type === 'digital' && !$productSetting->digital_product)) {
            return redirect()->back();
        }

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


    public function store(StoreRequest $request)
    {
        $response = ProductService::store($request);
        if ($response == true) {
            Session::flash('success', __('Created Successfully'));
        }
        return 'success';
    }

    public function edit($id)
    {
        $languages = app('languages');

        $product = Product::with([
            'sliderImage',
            'options.values',
            'variants.variantValues.optionValue.option',
        ])->findOrFail($id);

        $variantOptions = $product->options
            ->sortBy('position')
            ->values()
            ->map(function ($option) {
                $values = $option->values
                    ->sortBy('position')
                    ->pluck('value')
                    ->implode(', ');

                return [
                    'name' => $option->name,
                    'values' => $values,
                ];
            });

        $variantsData = $product->variants
            ->map(function ($variant) {
                $map = [];
                foreach ($variant->variantValues as $variantValue) {
                    $optionValue = $variantValue->optionValue;
                    if (!$optionValue || !$optionValue->option) {
                        continue;
                    }
                    $map[$optionValue->option->name] = $optionValue->value;
                }

                return [
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'status' => $variant->status,
                    'map' => $map,
                ];
            })
            ->values();
        $languages->map(function ($language) use ($product) {
            $language->content = $product->content->where('language_id', $language->id)->first();
            $language->categories = ProductCategory::where([['status', 1], ['language_id', $language->id]])
                ->orderBy('serial_number', 'desc')
                ->get();
            $language->is_added = $product->content->where('language_id', $language->id)->isNotEmpty();
        });
        $data['languages'] = $languages;
        $data['product'] = $product;
        $data['variantOptions'] = $variantOptions;
        $data['variantsData'] = $variantsData;

        return view('admin.product.edit', $data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $response = ProductService::update($request, $id);
        if ($response == true) {
            Session::flash('success', __('Updated Successfully'));
        }
        return 'success';
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

    private function deleteProductData(Product $product): void
    {
        // remove thumbnail
        if (!empty($product->thumbnail)) {
            @unlink(public_path('assets/img/product/') . $product->thumbnail);
        }

        // remove downloadable file
        if (!empty($product->download_file)) {
            @unlink(public_path('assets/img/product/file/') . $product->download_file);
        }

        // remove gallery images
        $sliderImages = SliderImage::where('item_id', $product->id)->where('item_type', 'product')->get();
        foreach ($sliderImages as $sliderImage) {
            if (!empty($sliderImage->image)) {
                @unlink(public_path('assets/img/product/gallery/') . $sliderImage->image);
            }
        }
        SliderImage::where('item_id', $product->id)->where('item_type', 'product')->delete();

        // remove contents
        ProductContent::where('product_id', $product->id)->delete();

        // remove variants
        $variantIds = ProductVariant::where('product_id', $product->id)->pluck('id');
        if ($variantIds->isNotEmpty()) {
            ProductVariantValue::whereIn('variant_id', $variantIds)->delete();
        }
        ProductVariant::where('product_id', $product->id)->delete();

        // remove options
        $optionIds = ProductOption::where('product_id', $product->id)->pluck('id');
        if ($optionIds->isNotEmpty()) {
            ProductOptionValue::whereIn('product_option_id', $optionIds)->delete();
        }
        ProductOption::where('product_id', $product->id)->delete();

        // finally remove product
        $product->delete();
    }

    public function delete(Request $request)
    {
        $productId = $request->product_id;
        $product = Product::findOrFail($productId);

        DB::beginTransaction();
        try {
            $this->deleteProductData($product);
            DB::commit();
            return redirect()->back()->with('success', __('Product deleted successfully'));
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', __('Failed to delete product'));
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];

        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $product = Product::find($id);
                if ($product) {
                    $this->deleteProductData($product);
                }
            }
            DB::commit();
            session()->flash('success', __('Products deleted successfully'));
            return response()->json(['status' => 'success'], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 'error'], 500);
        }
    }
}
