<?php

namespace App\Http\Controllers\FrontEnd;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\Admin\Language;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Frontend\ProductService;
use Illuminate\Support\Facades\Validator;
use App\Services\Frontend\CategoryService;
use App\Services\Plugins\GoogleRecaptchaService;

class ShopController extends Controller
{
    protected GoogleRecaptchaService $googleRecaptcha;

    /**
     * shop page load
     */
    public function index(Request $request)
    {
         if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $languageId = $currentLang->id;

        $data['categories'] = CategoryService::getHomeFeaturedCategories($languageId);
        $data['subcategories'] = ProductSubcategory::where('language_id', $languageId)
            ->where('status', 1)
            ->with('category:id,name')
            ->orderBy('serial_number', 'desc')
            ->get();
        $data['subcategoriesByCategory'] = $data['subcategories']->groupBy('category_id');

        $data['products'] = ProductService::getShopProducts($languageId, [
            'category' => $request->query('category'),
            'subcategory' => $request->query('subcategory'),
            'search' => $request->query('search'),
            'sort' => $request->query('sort', 'latest'),
            'min_price' => $request->query('min_price'),
            'max_price' => $request->query('max_price'),
        ]);

        $data['filters'] = [
            'category' => $request->query('category'),
            'subcategory' => $request->query('subcategory'),
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
         if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $languageId = $currentLang->id;
        $product = Product::with([
            'content' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            },
            'sliderImage',
            'variants.variantValues.optionValue',
            'reviews' => function ($q) {
                $q->latest('id')->take(12)->with('user');
            },
        ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->findOrFail($id);

        // Get product content for the current language
        $data['product_content'] = $product->content->first();

        // Check if flash sale is active
        $isFlashSaleActive = false;
        if ($product->flash_sale_status == 1 && Carbon::now()->between(
            Carbon::parse($product->flash_sale_start_at),
            Carbon::parse($product->flash_sale_end_at)
        )) {
            $isFlashSaleActive = true;
        }
        $data['isFlashSaleActive'] = $isFlashSaleActive;

        // Calculate average rating and review count
        $reviewCount = ($product->reviews_count ?? 0);
        $data['averageRating'] = $reviewCount > 0
            ? round((float) ($product->reviews_avg_rating ?? 0), 1)
            : 0;
        $data['reviewCount'] = $reviewCount;
        // Build review list
        $data['reviewList'] = $product->reviews
            ->map(function ($review) {
                $reviewer = $review->user;

                return [
                    'name' => $reviewer->name ?? $reviewer->username ?? 'User',
                    'rating' => (int) ($review->rating ?? 0),
                    'text' => (string) ($review->comment ?? ''),
                ];
            })
            ->filter(function ($review) {
                return $review['text'] !== '';
            })
            ->values()
            ->all();

        $flashDiscountPercent = (float) ($product->flash_sale_price ?? 0);
        if ($isFlashSaleActive) {
            $flashDiscountPercent = min($flashDiscountPercent, 100);
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
                    'price' => $isFlashSaleActive
                        ? max(((float) ($variant->price ?? $product->current_price ?? 0)) * (1 - ($flashDiscountPercent / 100)), 0)
                        : (float) ($variant->price ?? $product->current_price ?? 0),
                    'oldPrice' => $isFlashSaleActive
                        ? (float) ($variant->price ?? $product->current_price ?? 0)
                        : (float) ($product->previous_price ?? 0),
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
                'price' => $isFlashSaleActive
                    ? max(((float) ($product->current_price ?? 0)) * (1 - ($flashDiscountPercent / 100)), 0)
                    : (float) ($product->current_price ?? 0),
                'oldPrice' => $isFlashSaleActive
                    ? (float) ($product->current_price ?? 0)
                    : (float) ($product->previous_price ?? 0),
                'stock' => (int) ($product->stock ?? 0),
                'sku' => $product->sku ?? '',
            ];
        }

        $data['product'] = $product;
        $data['variants'] = $units;

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
         if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }

        $languageId = $currentLang->id;

        $product = Product::with([
            'content' => function ($q) use ($languageId) {
                $q->where('language_id', $languageId);
            },
            'sliderImage',
            'variants.variantValues.optionValue',
        ])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('id', $id)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $content = $product->content->first();
        $isFlashSaleActive = false;
        $flashDiscountPercent = (float) ($product->flash_sale_price ?? 0);
        if ($product->flash_sale_status == 1 && Carbon::now()->between(
            Carbon::parse($product->flash_sale_start_at),
            Carbon::parse($product->flash_sale_end_at)
        )) {
            $isFlashSaleActive = true;
        }

        if ($isFlashSaleActive) {
            $flashDiscountPercent = min($flashDiscountPercent, 100);
        }

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
                    'price' => $isFlashSaleActive
                        ? max(((float) ($variant->price ?? $product->current_price ?? 0)) * (1 - ($flashDiscountPercent / 100)), 0)
                        : (float) ($variant->price ?? $product->current_price ?? 0),
                    'oldPrice' => $isFlashSaleActive
                        ? (float) ($variant->price ?? $product->current_price ?? 0)
                        : (float) ($product->previous_price ?? 0),
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
                'price' => $isFlashSaleActive
                    ? max(((float) ($product->current_price ?? 0)) * (1 - ($flashDiscountPercent / 100)), 0)
                    : (float) ($product->current_price ?? 0),
                'oldPrice' => $isFlashSaleActive
                    ? (float) ($product->current_price ?? 0)
                    : (float) ($product->previous_price ?? 0),
                'stock' => (int) ($product->stock ?? 0),
                'sku' => $product->sku ?? '',
            ];
        }

        // Get category name
        $categoryName = 'Featured';
        if (!empty($content?->category_id)) {
            $categoryName = ProductCategory::where('id', $content->category_id)->value('name') ?: $categoryName;
        }

        $summaryText = strip_tags($content?->summary ?? '');
        $reviewCount = (int) ($product->reviews_count ?? 0);
        $averageRating = $reviewCount > 0
            ? round((float) ($product->reviews_avg_rating ?? 0), 1)
            : 0;

        $productDetail = [
            'id' => (string) $product->id,
            'name' => $content?->title ?: ('Product #' . $product->id),
            'category' => $categoryName,
            'rating' => $averageRating,
            'reviews' => $reviewCount,
            'badge' => $isFlashSaleActive ? 'Flash Sales' : $categoryName,
            'image' => $images[0] ?? asset('assets/admin/noimage.jpg'),
            'images' => $images,
            'summary' => $summaryText,
            'units' => $units,
            'stock' => (int) ($product->stock ?? 0),
            'url' => route('frontend.shop.details', ['id' => $product->id]),
        ];

        $html = view('front.partials.quickview-content', ['productDetail' => $productDetail])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'product' => $productDetail,
        ]);
    }

    public function storeReview(Request $request, $id)
    {
        $product = Product::query()->findOrFail($id);

        $rules = [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
        ];

        if ($this->googleRecaptcha->isEnabled()) {
            $rules['g-recaptcha-response'] = ['required', 'string'];
        }

        $validator = Validator::make($request->all(), $rules, [
            'g-recaptcha-response.required' => __('Please complete the Google Recaptcha verification.'),
        ]);

        if ($this->googleRecaptcha->isEnabled()) {
            $validator->after(function ($validator) use ($request) {
                if ($validator->errors()->has('g-recaptcha-response')) {
                    return;
                }

                if (!$this->googleRecaptcha->verify($request->input('g-recaptcha-response'), $request->ip())) {
                    $validator->errors()->add('g-recaptcha-response', __('Google Recaptcha verification failed. Please try again.'));
                }
            });
        }

        if ($validator->fails()) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $user = Auth::guard('web')->user();

        ProductReview::query()->updateOrCreate(
            [
                'product_id' => $product->id,
                'user_id' => $user->id,
            ],
            [
                'rating' => (int) $validated['rating'],
                'comment' => trim((string) $validated['comment']),
            ]
        );

        if ($request->ajax() || $request->expectsJson()) {
            $updatedProduct = Product::with([
                'reviews' => function ($q) {
                    $q->latest('id')->take(12)->with('user');
                },
            ])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->findOrFail($product->id);

            $reviewCount = (int) ($updatedProduct->reviews_count ?? 0);
            $averageRating = $reviewCount > 0
                ? round((float) ($updatedProduct->reviews_avg_rating ?? 0), 1)
                : 0;

            $reviewList = $updatedProduct->reviews
                ->map(function ($review) {
                    $reviewer = $review->user;

                    return [
                        'name' => $reviewer->name ?? $reviewer->username ?? 'User',
                        'rating' => (int) ($review->rating ?? 0),
                        'text' => (string) ($review->comment ?? ''),
                    ];
                })
                ->filter(function ($review) {
                    return $review['text'] !== '';
                })
                ->values()
                ->all();

            $html = view('front.partials.product-reviews-tab', [
                'productId' => (string) $updatedProduct->id,
                'productRating' => $averageRating,
                'productReviews' => $reviewCount,
                'productReviewList' => $reviewList,
                'successMessage' => 'Your review has been saved.',
            ])->render();

            return response()->json([
                'success' => true,
                'message' => 'Your review has been saved.',
                'html' => $html,
            ]);
        }

        return redirect()
            ->to(route('frontend.shop.details', ['id' => $product->id]) . '#reviews')
            ->with('success', 'Your review has been saved.');
    }
}
