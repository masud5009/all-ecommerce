<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;
use App\Models\ProductCategory;
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

        $product = Product::with([
            'content' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            },
            'sliderImage',
            'variants.variantValues.optionValue',
        ])
            ->where('id', $id)
            ->first();

        if (!$product) {
            abort(404);
        }

        $content = $product->content->first();

        // Build images array
        $images = [];
        if (!empty($product->thumbnail)) {
            $images[] = asset('assets/img/product/' . $product->thumbnail);
        }
        if ($product->sliderImage && $product->sliderImage->count() > 0) {
            foreach ($product->sliderImage as $sliderImg) {
                if (!empty($sliderImg->image)) {
                    $images[] = asset('assets/img/product/gallery/' . $sliderImg->image);
                }
            }
        }

        // Build units/variants
        $units = [];
        if ($product->variants && $product->variants->count() > 0) {
            foreach ($product->variants as $index => $variant) {
                $variantParts = collect($variant->variantValues ?? [])
                    ->map(fn($vv) => $vv->optionValue?->value)
                    ->filter()
                    ->values();

                $units[] = [
                    'variant_id' => $variant->id,
                    'label' => $variantParts->isNotEmpty() ? $variantParts->implode(', ') : ('Option ' . ($index + 1)),
                    'price' => (float) ($variant->price ?? $product->current_price ?? 0),
                    'oldPrice' => (float) ($product->previous_price ?? 0),
                    'stock' => (int) ($variant->stock ?? 0),
                    'sku' => $variant->sku ?? '',
                ];
            }
        }

        // Default unit if no variants
        if (empty($units)) {
            $units[] = [
                'variant_id' => null,
                'label' => '1 unit',
                'price' => (float) ($product->current_price ?? 0),
                'oldPrice' => (float) ($product->previous_price ?? 0),
                'stock' => (int) ($product->stock ?? 0),
                'sku' => $product->sku ?? '',
            ];
        }

        // Get category name
        $categoryName = 'Featured';
        if (!empty($content?->category_id)) {
            $categoryName = ProductCategory::where('id', $content->category_id)->value('name') ?: $categoryName;
        }

        // Summary and description
        $summaryText = $content?->summary ?? '';
        $descriptionText = $content?->description ?? $summaryText;

        $data['product'] = $product;
        $data['productDetail'] = [
            'id' => (string) $product->id,
            'name' => $content?->title ?: ('Product #' . $product->id),
            'category' => $categoryName,
            'rating' => 4.7,
            'reviews' => 142,
            'badge' => $categoryName,
            'image' => $images[0] ?? asset('assets/img/products/placeholder.png'),
            'images' => $images,
            'summary' => $summaryText,
            'description' => $descriptionText,
            'nutrition' => ['Fresh stock', 'Quality checked', 'Fast delivery', 'Secure packaging'],
            'reviewList' => [
                ['name' => 'Ariana', 'rating' => 5, 'text' => 'Great product quality and fast delivery.'],
                ['name' => 'Chris', 'rating' => 4, 'text' => 'Satisfied with the quality and packaging.'],
            ],
            'units' => $units,
            'isDeal' => ((float) ($product->previous_price ?? 0) > (float) ($product->current_price ?? 0)),
            'popular' => (bool) $product->is_popular,
            'stock' => (int) ($product->stock ?? 0),
        ];

        // You may also like products
        $data['youMayAlsoLikeProducts'] = ProductService::latestHomeProducts($languageId)
            ->reject(function ($item) use ($product) {
                return (int) $item->id === (int) $product->id;
            })
            ->take(4)
            ->values();

        return view('front.product-details', $data);
    }

    /**
     * Quick view product details (AJAX)
     */
    public function quickView($id)
    {
        $languageId = $this->currentLang->id;

        $product = Product::with([
            'content' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            },
            'sliderImage',
            'variants.variantValues.optionValue',
        ])
            ->where('id', $id)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $content = $product->content->first();

        // Build images array
        $images = [];
        if (!empty($product->thumbnail)) {
            $images[] = asset('assets/img/product/' . $product->thumbnail);
        }
        if ($product->sliderImage && $product->sliderImage->count() > 0) {
            foreach ($product->sliderImage as $sliderImg) {
                if (!empty($sliderImg->image)) {
                    $images[] = asset('assets/img/product/gallery/' . $sliderImg->image);
                }
            }
        }

        // Build units/variants
        $units = [];
        if ($product->variants && $product->variants->count() > 0) {
            foreach ($product->variants as $index => $variant) {
                $variantParts = collect($variant->variantValues ?? [])
                    ->map(fn($vv) => $vv->optionValue?->value)
                    ->filter()
                    ->values();

                $units[] = [
                    'variant_id' => $variant->id,
                    'label' => $variantParts->isNotEmpty() ? $variantParts->implode(', ') : ('Option ' . ($index + 1)),
                    'price' => (float) ($variant->price ?? $product->current_price ?? 0),
                    'oldPrice' => (float) ($product->previous_price ?? 0),
                    'stock' => (int) ($variant->stock ?? 0),
                ];
            }
        }

        // Default unit if no variants
        if (empty($units)) {
            $units[] = [
                'variant_id' => null,
                'label' => '1 unit',
                'price' => (float) ($product->current_price ?? 0),
                'oldPrice' => (float) ($product->previous_price ?? 0),
                'stock' => (int) ($product->stock ?? 0),
            ];
        }

        // Get category name
        $categoryName = 'Featured';
        if (!empty($content?->category_id)) {
            $categoryName = ProductCategory::where('id', $content->category_id)->value('name') ?: $categoryName;
        }

        $summaryText = strip_tags($content?->summary ?? '');

        $productDetail = [
            'id' => (string) $product->id,
            'name' => $content?->title ?: ('Product #' . $product->id),
            'category' => $categoryName,
            'rating' => 4.7,
            'reviews' => 142,
            'badge' => $categoryName,
            'image' => $images[0] ?? asset('assets/img/product/placeholder.png'),
            'images' => $images,
            'summary' => $summaryText,
            'units' => $units,
            'isDeal' => ((float) ($product->previous_price ?? 0) > (float) ($product->current_price ?? 0)),
            'stock' => (int) ($product->stock ?? 0),
            'url' => route('frontend.shop.details', ['id' => $product->id]),
        ];

        $html = view('front.partials.quickview-content', ['productDetail' => $productDetail])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }
}
