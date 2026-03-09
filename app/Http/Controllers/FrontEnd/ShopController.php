<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;
use App\Services\Frontend\CategoryService;

class ShopController extends Controller
{
    protected $currentLang;
    public function __construct()
    {
        if (session()->has('lang')) {
            $this->currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $this->currentLang = Language::where('is_default', 1)->first();
        }
    }

    /**
     * shop page load
     */
    public function shop(Request $request)
    {
        $languageId = $this->currentLang->id;

        $data['categories'] = CategoryService::getHomeFeaturedCategories($languageId);

        $data['products'] = ProductService::getShopProducts($languageId, [
            'category' => $request->query('category'),
            'search' => $request->query('search'),
            'sort' => $request->query('sort', 'latest'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
        ]);

        $data['filters'] = [
            'category' => $request->query('category'),
            'search' => $request->query('search'),
            'sort' => $request->query('sort', 'latest'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
        ];

        return view('front.shop', $data);
    }

    /**
     * product details load
     */
    public function details($id)
    {
        $languageId = $this->currentLang->id;

        $data['product'] = Product::with([
            'content' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            },
            'sliderImage'
        ])
            ->where('id', $id)
            ->first();

        return view('front.product-details', $data);
    }
}
