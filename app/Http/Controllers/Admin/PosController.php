<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Language;
use App\Models\Product;
use App\Models\ProductCoupon;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $languageId = Language::where('code', $request->language)->value('id');

        $data['products'] = Product::join('product_contents', 'products.id', 'product_contents.product_id')
            ->where('product_contents.language_id', $languageId)
            ->select('products.*', 'product_contents.title')
            ->get();

        $data['coupons'] = ProductCoupon::select('code', 'id', 'value', 'type')
            ->where('end_date', '>=', now()->toDateString())
            ->latest('created_at')
            ->get();

        $checkoutProducts = [];
        if (session()->has('posProduct')) {
            $checkoutProducts = session()->get('posProduct');
        }

        $checkoutTotal = [];
        $variationTotal = 0;
        foreach ($checkoutProducts as $checkoutProduct) {

            //if variations has then calculate here
            if (!is_null($checkoutProduct['variations'])) {
                $variationTotal = array_sum(array_map(function ($variations) {
                    return (int) $variations['price'];
                }, $checkoutProduct['variations']));
            }
            array_push($checkoutTotal, $checkoutProduct['subtotal']);
        }
        $checkoutTotal = array_sum($checkoutTotal) + $variationTotal;

        session()->put('posTotal', $checkoutTotal);

        $data['users'] = User::all();
        $data['checkoutProducts'] = $checkoutProducts;
        $totalItems = array_reduce($checkoutProducts, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);
        $data['totalItems'] = $totalItems;
        $data['totalAmount'] = session()->has('posTotal') ? session()->get('posTotal') : $checkoutTotal;

        return view('admin.pos.index', $data);
    }

    //add product without variations
    public function addProduct(Request $request)
    {
        $languageId = app('defaultLang')->id;
        $itemId = $request->id;
        $posProduct = session()->get('posProduct', []);

        if (!is_array($posProduct)) {
            $posProduct = [];
        }

        // Handle product removal
        if ($request->remove) {
            $posProduct = array_filter($posProduct, function ($item) use ($itemId) {
                return $item['id'] != $itemId;
            });
            session()->put('posProduct', $posProduct);
            return "removed";
        }


        $product = Product::join('product_contents', 'products.id', 'product_contents.product_id')
            ->where('product_contents.language_id', $languageId)
            ->select('products.*', 'product_contents.title')->findOrFail($request->id);

        if ($product->stock == 0 && $product->type != 'Digital') {
            return 'stock_out';
        }

        $productExists = false;

        // Update existing product quantity and subtotal
        foreach ($posProduct as &$item) {
            if ($item['id'] == $product->id) {
                if (!is_null($request->decrement)) {
                    $item['quantity'] = max(0, $item['quantity'] - 1);
                } else {
                    $item['quantity']++;
                }
                $item['subtotal'] = $product->current_price * $item['quantity'];
                $item['item_price'] = $product->current_price;
                $item['title'] = $product->title;
                $productExists = true;

                // Remove item if quantity becomes 0
                if ($item['quantity'] == 0) {
                    $posProduct = array_filter($posProduct, fn($p) => $p['id'] != $product->id);
                }
                break;
            }
        }

        // Add new product to the session if it doesn't exist
        if (!$productExists) {
            $posProduct[] = [
                'id' => $product->id,
                'quantity' => 1,
                'subtotal' => $product->current_price,
                'title' => $product->title,
                'variations' => null,
                'item_price' => $product->current_price
            ];
        }
        session()->put('posProduct', $posProduct);

        return "success";
    }

    //render variations and there options here
    public function getVariation(Request $request)
    {
        $defaultLang = app('defaultLang');

        $product = Product::join('product_contents', 'products.id', 'product_contents.product_id')
            ->where([['product_contents.language_id', $defaultLang->id], ['products.id', $request->id]])
            ->select('products.id', 'products.current_price', 'product_contents.title')
            ->first();

        $variations = ProductVariation::where([['product_id', $request->id], ['language_id', $defaultLang->id]])->get();

        return view('admin.pos.modal-content', compact('product', 'variations'));
    }

    public function addVariationProduct(Request $request)
    {
        $languageId = app('defaultLang')->id;
        //if user not select any variations then return
        $varCount = ProductVariation::where([['product_id', $request->id], ['language_id', $languageId]])->count();

        if (!$request->has('variations') || count($request->input('variations')) === 0 || count($request->input('variations')) < $varCount) {
            return response()->json(['status' => 'null-variations']);
        }

        $variations = $request->variations;
        $result = [];
        $outOfStockOptions = [];

        foreach ($variations as $variationId => $optionIndex) {
            $selectVariation = ProductVariation::find($variationId);

            if ($selectVariation) {
                $optionPrices = json_decode($selectVariation->option_price, true);
                $optionNames = json_decode($selectVariation->option_name, true);
                $optionStocks = json_decode($selectVariation->option_stock, true);

                if (isset($optionPrices[$optionIndex], $optionNames[$optionIndex], $optionStocks[$optionIndex])) {
                    $stock = $optionStocks[$optionIndex];
                    if ($stock > 0) {
                        $result[] = [
                            'variation_name' => $selectVariation->variant_name,
                            'variation_id' => $selectVariation->id,
                            'price' => $optionPrices[$optionIndex],
                            'option_name' => $optionNames[$optionIndex],
                            'option_key' => $optionIndex
                        ];
                        $posProduct = session()->get('posProduct', []);

                        $product = Product::join('product_contents', 'products.id', 'product_contents.product_id')
                            ->where([['product_contents.language_id', $languageId], ['products.id', $request->id]])
                            ->select('products.id', 'products.current_price', 'product_contents.title')
                            ->first();

                        $productExists = false;
                        // If the same product exists, increment the quantity and subtotal
                        foreach ($posProduct as &$item) {
                            if ($item['id'] == $product->id) {
                                foreach ($item['variations'] as $itemVariation) {
                                    if ($itemVariation['variation_id'] == $variationId && $itemVariation['option_name'] == $optionNames[$optionIndex]) {
                                        $item['quantity']++;
                                        $item['subtotal'] = $product->current_price * $item['quantity'];
                                        $item['item_price'] = $product->current_price;
                                        $productExists = true;
                                        break;
                                    }
                                }
                            }
                        }
                        // Add a new product if it doesn't already exist in the session
                        if (!$productExists) {
                            $posProduct[] = [
                                'id' => $product->id,
                                'quantity' => 1,
                                'subtotal' => $product->current_price,
                                'title' => $product->title,
                                'variations' => $result,
                                'item_price' => $product->current_price
                            ];
                        }
                    } else {
                        $outOfStockOptions[] = [
                            'variation_name' => $selectVariation->variant_name,
                            'option_name' => $optionNames[$optionIndex],
                        ];
                    }
                }
            }
        }

        //if variations is out of stock then return
        if (!empty($outOfStockOptions)) {
            return response()->json(['status' => 'stock_out', 'data' => $outOfStockOptions]);
        }
        session()->put('posProduct', $posProduct);
        return response()->json(['status' => 'success']);
    }

    public function itemSearch(Request $request)
    {
        $languageId = app('defaultLang')->id;

        // Search query
        $products = Product::join('product_contents', 'products.id', 'product_contents.product_id')
            ->where('product_contents.language_id', $languageId)
            ->where('product_contents.title', 'like', '%' . $request->search . '%')  // Search by title
            ->select('products.*', 'product_contents.title')
            ->get();

        // Return the updated product list
        return response()->view('admin.pos.product-list', compact('products'));
    }
}
