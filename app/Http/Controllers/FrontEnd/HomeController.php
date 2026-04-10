<?php

namespace App\Http\Controllers\FrontEnd;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\HomeSlider;
use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Models\ProductContent;
use App\Models\ProductCategory;
use App\Models\HomeFreshnessItem;
use App\Models\HomeSectionSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Frontend\ProductService;
use App\Services\Frontend\CategoryService;

class HomeController extends Controller
{

    /**
     * Display the home page with
     * -featured products
     * -flash sale products
     * -popular products
     * -all categories
     * -hero sliders
     * -features sections
     */
    public function index()
    {

        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }


        $languageId = $currentLang->id;
        $data['homeCategories'] = CategoryService::getHomeFeaturedCategories($languageId);

        $featuredProducts = ProductService::getHomeFeaturedProducts($languageId);
        $flashSaleCardProducts = ProductService::getHomeFlashSaleProducts($languageId);
        $popularProducts = ProductService::latestHomeProducts($languageId);

        $data['featuredProducts'] = $featuredProducts;
        $data['flashSaleCardProducts'] = $flashSaleCardProducts;
        $data['popularProducts'] = $popularProducts;

        $data['homeSliders'] = HomeSlider::where('status', 1)
            ->where('language_id', $languageId)
            ->orderBy('serial_number', 'asc')
            ->get();
        // dd($data['homeSliders']);
        $data['sectionTitles'] = HomeSectionSetting::where('language_id', $languageId)->first();
        $data['freshnessLeftItems'] = HomeFreshnessItem::where('language_id', $languageId)
            ->where('status', 1)
            ->where('position', 'left')
            ->orderBy('serial_number', 'asc')
            ->get();
        $data['freshnessRightItems'] = HomeFreshnessItem::where('language_id', $languageId)
            ->where('status', 1)
            ->where('position', 'right')
            ->orderBy('serial_number', 'asc')
            ->get();

        return view('front.grocery.index', $data);
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function productDetails(Request $request, $product = null)
    {
        // Preserve existing client-side behavior for legacy non-numeric ids.
        if (is_null($product) && !$request->has('product') && $request->filled('id') && !is_numeric($request->query('id'))) {
            return view('front.home.product-details');
        }

        $languageId = $this->currentLang->id;
        $rawProductId = $product ?? $request->query('product', $request->query('id', 0));
        $productId = (int) $rawProductId;

        $productQuery = Product::query()
            ->with([
                'sliderImage',
                'variants' => function ($query) {
                    $query->where('status', 1)
                        ->with(['variantValues.optionValue.option']);
                },
                'content' => function ($query) use ($languageId) {
                    if ($languageId) {
                        $query->where('language_id', $languageId);
                    }
                },
            ])
            ->where('status', 1);

        if ($productId > 0) {
            $productQuery->where('id', $productId);
        } else {
            $productQuery->orderByDesc('id');
        }

        $product = $productQuery->first();

        if (!$product) {
            abort(404);
        }

        $content = $product->content->first();
        $flashDiscountPercent = (float) ($product->flash_sale_price ?? 0);
        $isFlashSaleActive =
            (int) ($product->flash_sale_status ?? 0) === 1 &&
            $flashDiscountPercent > 0 &&
            !empty($product->flash_sale_start_at) &&
            !empty($product->flash_sale_end_at) &&
            Carbon::now()->between(
                Carbon::parse($product->flash_sale_start_at),
                Carbon::parse($product->flash_sale_end_at)
            );

        if ($isFlashSaleActive) {
            $flashDiscountPercent = min($flashDiscountPercent, 100);
        }

        if (!$content) {
            $content = ProductContent::where('product_id', $product->id)
                ->orderByDesc('id')
                ->first();
        }

        $categoryName = 'Featured';
        if (!empty($content?->category_id)) {
            $categoryName = ProductCategory::where('id', $content->category_id)->value('name') ?: $categoryName;
        }

        $images = [];
        if (!empty($product->thumbnail)) {
            $images[] = asset('assets/img/product/' . $product->thumbnail);
        }

        foreach ($product->sliderImage as $slider) {
            if (!empty($slider->image)) {
                $images[] = asset('assets/img/product/gallery/' . $slider->image);
            }
        }

        $summaryText = html_entity_decode(
            strip_tags((string) ($content?->summary ?: 'No summary available.')),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );
        $summaryText = preg_replace('/\s+/', ' ', trim($summaryText));

        $descriptionText = html_entity_decode(
            strip_tags((string) ($content?->description ?: $content?->summary ?: 'No description available.')),
            ENT_QUOTES | ENT_HTML5,
            'UTF-8'
        );
        $descriptionText = preg_replace('/\s+/', ' ', trim($descriptionText));

        $units = [];
        if ((int) $product->has_variants === 1 && $product->variants->isNotEmpty()) {
            foreach ($product->variants as $index => $variant) {
                $variantParts = $variant->variantValues
                    ->sortBy(function ($variantValue) {
                        return optional(optional($variantValue->optionValue)->option)->position ?? 0;
                    })
                    ->map(function ($variantValue) {
                        $option = optional($variantValue->optionValue)->option;
                        $value = optional($variantValue->optionValue)->value;

                        if (!$option || $value === null) {
                            return null;
                        }

                        return $option->name . ': ' . $value;
                    })
                    ->filter()
                    ->values();

                $units[] = [
                    'label' => $variantParts->isNotEmpty() ? $variantParts->implode(', ') : ('Option ' . ($index + 1)),
                    'price' => $isFlashSaleActive
                        ? max(((float) ($variant->price ?? $product->current_price ?? 0)) * (1 - ($flashDiscountPercent / 100)), 0)
                        : (float) ($variant->price ?? $product->current_price ?? 0),
                    'oldPrice' => $isFlashSaleActive
                        ? (float) ($variant->price ?? $product->current_price ?? 0)
                        : (float) ($product->previous_price ?? 0),
                ];
            }
        } else {
            $units[] = [
                'label' => '1 unit',
                'price' => $isFlashSaleActive
                    ? max(((float) ($product->current_price ?? 0)) * (1 - ($flashDiscountPercent / 100)), 0)
                    : (float) ($product->current_price ?? 0),
                'oldPrice' => $isFlashSaleActive
                    ? (float) ($product->current_price ?? 0)
                    : (float) ($product->previous_price ?? 0),
            ];
        }

        $data['productDetailData'] = [
            'id' => (string) $product->id,
            'name' => $content?->title ?: ('Product #' . $product->id),
            'category' => $categoryName,
            'rating' => 4.7,
            'reviews' => 142,
            'badge' => $categoryName,
            'image' => $images[0] ?? null,
            'images' => $images,
            'summary' => $summaryText,
            'description' => $descriptionText,
            'nutrition' => ['Fresh stock', 'Quality checked', 'Fast delivery', 'Secure packaging'],
            'reviewList' => [
                ['name' => 'Ariana', 'rating' => 5, 'text' => 'Great product quality and fast delivery.'],
                ['name' => 'Chris', 'rating' => 4, 'text' => 'Satisfied with the quality and packaging.'],
            ],
            'units' => $units,
            'isDeal' => $isFlashSaleActive || collect($units)->contains(function ($unit) {
                return (float) ($unit['oldPrice'] ?? 0) > (float) ($unit['price'] ?? 0);
            }),
            'popular' => true,
        ];

        $data['youMayAlsoLikeProducts'] = ProductService::latestHomeProducts($languageId)
            ->reject(function ($item) use ($product) {
                return (int) $item->id === (int) $product->id;
            })
            ->take(4)
            ->values();

        return view('front.home.product-details', $data);
    }

    public function blog()
    {
        return view('frontend.blog');
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function invoice()
    {
        $data['bs'] = DB::table('settings')->select('website_logo', 'website_title', 'website_color')->first();
        $data['order']  = Order::first();
        $data['orderItems'] = OrderItem::where('order_id', $data['order']->id)->get();
        // dd($data['orderItems']);
        $data['lang'] = app('defaultLang')->id;

        // Order::where('id', $orderInfo->id)->update([
        //     'invoice_number' => $fileName
        // ]);

        return view('admin.invoices.product-invoice', $data);
    }

    /**
     * Change the current language of the application and store it in the session.
     */
    public function changeLanguage($code)
    {
        session()->put('lang', $code);
        app()->setLocale($code);
        return redirect()->back();
    }
}
