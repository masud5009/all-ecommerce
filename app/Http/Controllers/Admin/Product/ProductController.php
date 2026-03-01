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
use App\Models\ProductVariantSerialBatch;
use App\Models\ProductCategory;
use App\Imports\ProductImport;
use App\Exports\ProductImportTemplate;
use App\Http\Helpers\ImageUpload;
use App\Models\ProductOptionValue;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVariantValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Services\Shop\ProductService;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $language_id = Language::where('code', $request->language)->pluck('id');
        $hasFlashSaleColumns = Schema::hasColumn('products', 'flash_sale_status')
            && Schema::hasColumn('products', 'flash_sale_price')
            && Schema::hasColumn('products', 'flash_sale_start_at')
            && Schema::hasColumn('products', 'flash_sale_end_at');

        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status');
        $stock = $request->input('stock');
        $variantType = $request->input('variant_type');
        $productType = $request->input('product_type');

        $productQuery = Product::join('product_contents', 'product_contents.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
            ->where('product_contents.language_id', $language_id)
            ->where('product_categories.language_id', $language_id);

        if ($search !== '') {
            $productQuery->where(function ($query) use ($search) {
                $query->where('product_contents.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('product_categories.name', 'LIKE', '%' . $search . '%');
            });
        }

        if (in_array((string) $status, ['0', '1'], true)) {
            $productQuery->where('products.status', (int) $status);
        }

        if ($variantType === 'variant') {
            $productQuery->where('products.has_variants', 1);
        } elseif ($variantType === 'non_variant') {
            $productQuery->where('products.has_variants', 0);
        }

        if (in_array((string) $productType, ['physical', 'digital'], true)) {
            $productQuery->where('products.type', $productType);
        }

        $selectColumns = [
            'products.id',
            'products.thumbnail',
            'products.status',
            'products.stock',
            'products.has_variants',
            'products.current_price',
            'products.previous_price',
            'product_contents.title',
            'product_categories.name as categoryName',
        ];

        if ($hasFlashSaleColumns) {
            $selectColumns[] = 'products.flash_sale_status';
            $selectColumns[] = 'products.flash_sale_price';
            $selectColumns[] = 'products.flash_sale_start_at';
            $selectColumns[] = 'products.flash_sale_end_at';
        }

        $products = $productQuery
            ->select($selectColumns)
            ->orderBy('products.created_at', 'desc')
            ->get();

        $products->transform(function ($product) use ($hasFlashSaleColumns) {
            $product->available_stock = (int) $product->stock;

            if (!$hasFlashSaleColumns) {
                $product->flash_sale_status = 0;
                $product->flash_sale_price = null;
                $product->flash_sale_start_at = null;
                $product->flash_sale_end_at = null;
            } else {
                $product->flash_sale_status = (int) ($product->flash_sale_status ?? 0);
            }

            return $product;
        });

        $variantProductIds = $products
            ->filter(function ($product) {
                return (int) $product->has_variants === 1;
            })
            ->pluck('id')
            ->all();

        if (!empty($variantProductIds)) {
            $variants = ProductVariant::whereIn('product_id', $variantProductIds)
                ->where('status', 1)
                ->get(['id', 'product_id', 'stock', 'track_serial']);

            $variantIds = $variants->pluck('id')->all();
            $serialStockByVariant = collect();

            if (!empty($variantIds)) {
                $serialStockByVariant = ProductVariantSerialBatch::whereIn('variant_id', $variantIds)
                    ->selectRaw('variant_id, COALESCE(SUM(qty - sold_qty), 0) as available_stock')
                    ->groupBy('variant_id')
                    ->pluck('available_stock', 'variant_id');
            }

            $variantStockByProduct = [];

            foreach ($variants as $variant) {
                $availableStock = (int) $variant->stock;

                if ((int) $variant->track_serial === 1) {
                    $availableStock = (int) ($serialStockByVariant[$variant->id] ?? 0);
                }

                $variantStockByProduct[$variant->product_id] =
                    ($variantStockByProduct[$variant->product_id] ?? 0) + max(0, $availableStock);
            }

            $products->transform(function ($product) use ($variantStockByProduct) {
                if ((int) $product->has_variants === 1) {
                    $product->available_stock = (int) ($variantStockByProduct[$product->id] ?? 0);
                }

                return $product;
            });
        }

        if ($stock === 'in_stock') {
            $products = $products->filter(function ($product) {
                return (int) $product->available_stock > 0;
            })->values();
        } elseif ($stock === 'out_of_stock') {
            $products = $products->filter(function ($product) {
                return (int) $product->available_stock <= 0;
            })->values();
        } elseif ($stock === 'low_stock') {
            $products = $products->filter(function ($product) {
                $available = (int) $product->available_stock;
                return $available >= 1 && $available <= 5;
            })->values();
        }

        $data['products'] = $products;
        $data['product_setting'] = ProductSetting::first();
        $data['search'] = $search;
        $data['hasFlashSaleColumns'] = $hasFlashSaleColumns;

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

    public function importForm()
    {
        return view('admin.product.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt,xlsx,xls',
        ]);

        $import = new ProductImport();
        Excel::import($import, $request->file('import_file'));

        if ($import->inserted > 0 || $import->updated > 0) {
            $messageParts = [];
            if ($import->inserted > 0) {
                $messageParts[] = "Imported {$import->inserted}";
            }
            if ($import->updated > 0) {
                $messageParts[] = "Updated {$import->updated}";
            }
            session()->flash('success', implode(', ', $messageParts) . ' products.');
        }

        if ($import->skipped > 0) {
            session()->flash('warning', "{$import->skipped} rows skipped. Please check the errors below.");
        }

        return redirect()->back()->with('import_errors', $import->errors);
    }

    public function importTemplate()
    {
        return Excel::download(new ProductImportTemplate(), 'product-import-template.csv');
    }

    public function importTemplateExcel()
    {
        return Excel::download(new ProductImportTemplate(), 'product-import-template.xlsx');
    }


    public function store(StoreRequest $request)
    {
        try {
            ProductService::store($request);
            Session::flash('success', __('Created Successfully'));

            return 'success';
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('Failed to create product'),
            ], 422);
        }
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
                    'image_url' => $variant->image ? asset('assets/img/product/variant/' . $variant->image) : null,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                    'status' => $variant->status,
                    'serial_start' => $variant->serial_start,
                    'serial_end' => $variant->serial_end,
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
        try {
            ProductService::update($request, $id);
            Session::flash('success', __('Updated Successfully'));

            return 'success';
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage() ?: __('Failed to update product'),
            ], 422);
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

    public function updateFlashSale(Request $request)
    {
        $hasFlashSaleColumns = Schema::hasColumn('products', 'flash_sale_status')
            && Schema::hasColumn('products', 'flash_sale_price')
            && Schema::hasColumn('products', 'flash_sale_start_at')
            && Schema::hasColumn('products', 'flash_sale_end_at');

        if (!$hasFlashSaleColumns) {
            return redirect()->back()->with('error', __('Please run migration first to enable flash sale.'));
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'flash_sale_status' => 'required|in:0,1',
            'flash_sale_price' => 'nullable|required_if:flash_sale_status,1|numeric|min:0',
            'flash_sale_start_at' => 'nullable|date',
            'flash_sale_end_at' => 'nullable|date|after:flash_sale_start_at',
        ]);

        $product = Product::findOrFail((int) $validated['product_id']);
        $status = (int) $validated['flash_sale_status'];

        if ($status === 0) {
            $product->flash_sale_status = 0;
            $product->flash_sale_price = null;
            $product->flash_sale_start_at = null;
            $product->flash_sale_end_at = null;
            $product->save();

            return redirect()->back()->with('success', __('Flash sale disabled successfully'));
        }

        $flashSalePrice = (float) ($validated['flash_sale_price'] ?? 0);
        $currentPrice = (float) ($product->current_price ?? 0);

        if ($currentPrice > 0 && $flashSalePrice >= $currentPrice) {
            return redirect()->back()->with('error', __('Flash sale price must be less than current price.'));
        }

        $product->flash_sale_status = 1;
        $product->flash_sale_price = $flashSalePrice;
        $product->flash_sale_start_at = !empty($validated['flash_sale_start_at'])
            ? Carbon::parse($validated['flash_sale_start_at'])
            : null;
        $product->flash_sale_end_at = !empty($validated['flash_sale_end_at'])
            ? Carbon::parse($validated['flash_sale_end_at'])
            : null;
        $product->save();

        return redirect()->back()->with('success', __('Flash sale updated successfully'));
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
        $variants = ProductVariant::where('product_id', $product->id)->get(['id', 'image']);
        $variantIds = $variants->pluck('id');
        if ($variantIds->isNotEmpty()) {
            foreach ($variants as $variant) {
                if (!empty($variant->image)) {
                    @unlink(public_path('assets/img/product/variant/') . $variant->image);
                }
            }
            ProductVariantValue::whereIn('variant_id', $variantIds)->delete();
            \App\Models\ProductVariantSerialBatch::whereIn('variant_id', $variantIds)->delete();
            \App\Models\ProductVariantSoldSerial::whereIn('variant_id', $variantIds)->delete();
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
