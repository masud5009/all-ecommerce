<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductExportReport;
use App\Http\Controllers\Controller;
use App\Models\Admin\Language;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    public function stockOverview(Request $request)
    {
        $currentLang = Language::where('code', $request->language)->pluck('id')->firstOrFail();
        $data['currentLang'] = $currentLang;

        //search by stock
        $stockIds = [];
        if ($request->stock_type) {
            $productIds = Product::pluck('id')->toArray();

            $variations = ProductVariation::where('language_id', $currentLang)
                ->whereIn('product_id', $productIds)
                ->get();

            foreach ($variations as $variation) {
                $stocks = json_decode($variation->option_stock, true);
                $productId = $variation->product_id;

                if ($request->stock_type == 'stock_in') {
                    if (array_sum($stocks) > 0) {
                        $stockIds[] = $productId;
                    }
                } elseif ($request->stock_type == 'stock_out') {
                    if (array_sum($stocks) <= 0) {
                        $stockIds[] = $productId;
                    }
                }
            }

            // Fetch products without variations
            $productsWithoutVariations = Product::whereNotIn('id', function ($query) use ($currentLang) {
                $query->select('product_id')
                    ->from('product_variations')
                    ->where('language_id', $currentLang);
            })->get();

            // Check stock for products without variations
            foreach ($productsWithoutVariations as $product) {
                if ($request->stock_type == 'stock_in' && $product->stock > 0) {
                    $stockIds[] = $product->id;
                } elseif ($request->stock_type == 'stock_out' && $product->stock <= 0) {
                    $stockIds[] = $product->id;
                }
            }

            $stockIds = array_unique($stockIds);
        }

        $products = Product::join('product_contents', 'product_contents.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
            ->where('product_contents.language_id', $currentLang)
            ->where('product_categories.language_id', $currentLang)
            ->when($request->category, function ($query) use ($request) {
                $query->where('product_contents.category_id', $request->category);
            })
            ->when($request->stock_type, function ($query) use ($stockIds) {
                return $query->whereIn('products.id', $stockIds);
            })
            ->when($request->title, function ($query) use ($request) {
                $query->where('product_contents.title', 'LIKE', '%' . $request->title . '%')
                    ->orWhere('products.sku', 'LIKE', '%' . $request->title . '%');
            })
            ->select(
                'products.id',
                'products.created_at',
                'products.sku',
                'products.stock',
                'products.last_restock_qty',
                'products.current_price',
                'products.previous_price',
                'products.type',
                'products.status',
                'product_contents.title',
                'product_categories.name as categoryName'
            )
            ->orderBy('products.created_at', 'DESC');

        //session put for export excel file
        session()->put('product_list', $products->get());
        $data['products'] = $products->paginate(10);

        $data['categories'] = ProductCategory::where('language_id', $currentLang)
            ->orderBy('serial_number', 'ASC')->get();

        return view('admin.inventory.stock', $data);
    }

    public function updateList(Request $request)
    {
        $currentLang = Language::where('code', $request->language)->select('id', 'code')->firstOrFail();
        $data['currentLang'] = $currentLang;

        //search by stock
        $stockIds = [];
        if ($request->stock_type) {
            $productIds = Product::pluck('id')->toArray();

            $variations = ProductVariation::where('language_id', $currentLang->id)
                ->whereIn('product_id', $productIds)
                ->get();

            foreach ($variations as $variation) {
                $stocks = json_decode($variation->option_stock, true);
                $productId = $variation->product_id;

                if ($request->stock_type == 'stock_in') {
                    if (array_sum($stocks) > 0) {
                        $stockIds[] = $productId;
                    }
                } elseif ($request->stock_type == 'stock_out') {
                    if (array_sum($stocks) <= 0) {
                        $stockIds[] = $productId;
                    }
                }
            }

            // Fetch products without variations
            $currentLangCode = $currentLang->id;
            $productsWithoutVariations = Product::whereNotIn('id', function ($query) use ($currentLangCode) {
                $query->select('product_id')
                    ->from('product_variations')
                    ->where('language_id', $currentLangCode);
            })->get();

            // Check stock for products without variations
            foreach ($productsWithoutVariations as $product) {
                if ($request->stock_type == 'stock_in' && $product->stock > 0) {
                    $stockIds[] = $product->id;
                } elseif ($request->stock_type == 'stock_out' && $product->stock <= 0) {
                    $stockIds[] = $product->id;
                }
            }

            $stockIds = array_unique($stockIds);
        }

        $data['products'] = Product::join('product_contents', 'product_contents.product_id', 'products.id')
            ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
            ->where('product_contents.language_id', $currentLang->id)
            ->where('product_categories.language_id', $currentLang->id)
            ->when($request->category, function ($query) use ($request) {
                $query->where('product_contents.category_id', $request->category);
            })
            ->when($request->stock_type, function ($query) use ($stockIds) {
                return $query->whereIn('products.id', $stockIds);
            })
            ->when($request->title, function ($query) use ($request) {
                $query->where('product_contents.title', 'LIKE', '%' . $request->title . '%')
                    ->orWhere('products.sku', 'LIKE', '%' . $request->title . '%');
            })
            ->select(
                'products.id',
                'products.sku',
                'products.stock',
                'products.current_price',
                'products.previous_price',
                'products.type',
                'product_contents.title',
                'product_categories.name as categoryName'
            )
            ->orderBy('products.created_at', 'DESC')
            ->paginate(10);

        $data['categories'] = ProductCategory::where('language_id', $currentLang->id)
            ->orderBy('serial_number', 'ASC')->get();

        return view('admin.inventory.stock-list.index', $data);
    }

    public function updateStock(Request $request)
    {
        Product::findOrFail($request->id)->update(['stock' => $request->stock]);
        session()->flash('success', __('Update successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function exportReport(Request $request)
    {
        $productList = [];
        if (session()->has('product_list')) {
            $productList = session()->get('product_list');
        } else {
            $currentLang = Language::where('code', $request->language)->select('id', 'code')->firstOrFail();
            $data['currentLang'] = $currentLang;

            $productList = Product::join('product_contents', 'product_contents.product_id', 'products.id')
                ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
                ->where('product_contents.language_id', $currentLang->id)
                ->where('product_categories.language_id', $currentLang->id)
                ->select(
                    'products.id',
                    'products.created_at',
                    'products.sku',
                    'products.stock',
                    'products.last_restock_qty',
                    'products.current_price',
                    'products.previous_price',
                    'products.type',
                    'products.status',
                    'product_contents.title',
                    'product_categories.name as categoryName'
                )
                ->orderBy('products.created_at', 'DESC')
                ->paginate(10);
        }

        if (count($productList) == 0) {
            session()->flash('warning', 'No product found to export!');
            return redirect()->back();
        } else {
            return Excel::download(new ProductExportReport($productList), 'product-list.csv');
        }
    }
    public function exportReportExcel(Request $request)
    {
        $productList = [];
        if (session()->has('product_list')) {
            $productList = session()->get('product_list');
        } else {
            $currentLang = Language::where('code', $request->language)->select('id', 'code')->firstOrFail();
            $data['currentLang'] = $currentLang;

            $productList = Product::join('product_contents', 'product_contents.product_id', 'products.id')
                ->join('product_categories', 'product_categories.id', 'product_contents.category_id')
                ->where('product_contents.language_id', $currentLang->id)
                ->where('product_categories.language_id', $currentLang->id)
                ->select(
                    'products.id',
                    'products.created_at',
                    'products.sku',
                    'products.stock',
                    'products.last_restock_qty',
                    'products.current_price',
                    'products.previous_price',
                    'products.type',
                    'products.status',
                    'product_contents.title',
                    'product_categories.name as categoryName'
                )
                ->orderBy('products.created_at', 'DESC')
                ->paginate(10);
        }

        if (count($productList) == 0) {
            session()->flash('warning', 'No product found to export!');
            return redirect()->back();
        } else {
            return Excel::download(new ProductExportReport($productList), 'product-list.xlsx');
        }
    }
}
