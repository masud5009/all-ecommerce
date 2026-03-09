<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\HomeSlider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Admin\Package;
use App\Models\Admin\Language;
use App\Models\ProductContent;
use App\Models\ProductCategory;
use App\Models\HomeSectionSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use App\Services\Frontend\ProductService;
use App\Services\Frontend\CategoryService;

class HomeController extends Controller
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

    public function changeLanguage($code)
    {
        session()->put('lang', $code);
        app()->setLocale($code);
        return redirect()->back();
    }

    public function index()
    {
        $languageId = $this->currentLang->id;
        $data['homeCategories'] = CategoryService::getHomeFeaturedCategories($languageId);

        $featuredProducts = ProductService::getHomeFeaturedProducts($languageId);
        $flashSaleCardProducts = ProductService::getHomeFlashSaleProducts($languageId);
        $popularProducts = ProductService::latestHomeProducts($languageId);

        $data['featuredProducts'] = $featuredProducts;
        $data['flashSaleCardProducts'] = $flashSaleCardProducts;
        $data['popularProducts'] = $popularProducts;

        // Build flash sale deal hero card from first flash sale product
        $data['flashSaleDeal'] = null;
        if ($flashSaleCardProducts->isNotEmpty()) {
            $firstFlash = $flashSaleCardProducts->first();
            $flashContent = $firstFlash->content->first();
            $flashTitle = $flashContent->title ?? 'Flash Sale Deal';
            $flashSummary = trim(preg_replace('/\s+/', ' ', strip_tags((string) ($flashContent->summary ?? $flashContent->description ?? 'Limited time offer on selected items.'))));

            $currentPrice = (float) ($firstFlash->current_price ?? 0);
            $flashAmount = (float) ($firstFlash->flash_sale_price ?? 0);
            $salePrice = max($currentPrice - $flashAmount, 0);
            $oldPrice = $currentPrice;
            $saveAmount = $oldPrice - $salePrice;
            $savePercent = $oldPrice > 0 ? round(($saveAmount / $oldPrice) * 100) : 0;

            $endAt = \Carbon\Carbon::parse($firstFlash->flash_sale_end_at);
            $countdownSeconds = max(0, now()->diffInSeconds($endAt, false));

            $thumbnail = !empty($firstFlash->thumbnail)
                ? asset('assets/img/product/' . $firstFlash->thumbnail)
                : '';

            $stockLabel = ($firstFlash->stock ?? 0) > 0 ? __('In Stock') : __('Stock Out');

            $data['flashSaleDeal'] = (object) [
                'title' => $flashTitle,
                'summary' => $flashSummary ?: 'Limited time offer on selected items.',
                'sale_price' => $salePrice,
                'old_price' => $oldPrice,
                'save_amount' => $saveAmount,
                'save_percent' => $savePercent,
                'countdown_seconds' => (int) $countdownSeconds,
                'image' => $thumbnail,
                'stock_label' => $stockLabel,
                'details_url' => route('frontend.shop.details', ['id' => $firstFlash->id]),
            ];
        }

        $data['homeSliders'] = HomeSlider::where('status', 1)
            ->where('language_id', $languageId)
            ->orderBy('serial_number', 'asc')
            ->get();
        $data['sectionTitles'] = HomeSectionSetting::where('language_id', $languageId)->first();

        return view('front.home.index', $data);
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

        $languageId = $this->getCurrentLanguageId();
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
                    'price' => (float) ($variant->price ?? $product->current_price ?? 0),
                    'oldPrice' => (float) ($product->previous_price ?? 0),
                ];
            }
        } else {
            $units[] = [
                'label' => '1 unit',
                'price' => (float) ($product->current_price ?? 0),
                'oldPrice' => (float) ($product->previous_price ?? 0),
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
            'isDeal' => ((float) ($product->previous_price ?? 0) > (float) ($product->current_price ?? 0)),
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

    private function getCurrentLanguageId(): ?int
    {
        $languageCode = session('lang');
        $currentLanguage = null;

        if (!empty($languageCode)) {
            $currentLanguage = Language::where('code', $languageCode)->first();
        }

        if (!$currentLanguage) {
            $currentLanguage = Language::where('is_default', 1)->first() ?? app('defaultLang');
        }

        return $currentLanguage?->id;
    }
}
