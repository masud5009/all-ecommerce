<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin\Package;
use App\Models\Admin\Language;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function changeLanguage($code)
    {
        session()->put('lang', $code);
        app()->setLocale($code);
        return redirect()->back();
    }

    public function index()
    {
        //packages
        $data['packages'] = Package::where('status', 1)->get();

        $languageId = $this->getCurrentLanguageId();
        $hasFeaturedColumn = Schema::hasColumn('products', 'featured');

        $featuredProducts = Product::query()
            ->join('product_contents', function ($join) use ($languageId) {
                $join->on('product_contents.product_id', '=', 'products.id');
                if ($languageId) {
                    $join->where('product_contents.language_id', '=', $languageId);
                }
            })
            ->leftJoin('product_categories', function ($join) use ($languageId) {
                $join->on('product_categories.id', '=', 'product_contents.category_id');
                if ($languageId) {
                    $join->where('product_categories.language_id', '=', $languageId);
                }
            })
            ->where('products.status', 1)
            ->when($hasFeaturedColumn, function ($query) {
                $query->where('products.featured', 1);
            })
            ->select(
                'products.id',
                'products.thumbnail',
                'products.current_price',
                'products.previous_price',
                'products.stock',
                'products.has_variants',
                'product_contents.title',
                'product_contents.summary',
                'product_contents.slug',
                'product_categories.name as category_name'
            )
            ->orderByDesc('products.id')
            ->limit(8)
            ->get();

        $featuredProducts = $this->hydrateProductCards($featuredProducts);

        $flashSaleProducts = collect();
        $flashSaleCountdownSeconds = 8132;

        if ($this->hasFlashSaleColumns()) {
            $now = now();

            $flashSaleProducts = Product::query()
                ->join('product_contents', function ($join) use ($languageId) {
                    $join->on('product_contents.product_id', '=', 'products.id');
                    if ($languageId) {
                        $join->where('product_contents.language_id', '=', $languageId);
                    }
                })
                ->leftJoin('product_categories', function ($join) use ($languageId) {
                    $join->on('product_categories.id', '=', 'product_contents.category_id');
                    if ($languageId) {
                        $join->where('product_categories.language_id', '=', $languageId);
                    }
                })
                ->where('products.status', 1)
                ->where('products.flash_sale_status', 1)
                ->whereNotNull('products.flash_sale_price')
                ->where(function ($query) use ($now) {
                    $query->whereNull('products.flash_sale_start_at')
                        ->orWhere('products.flash_sale_start_at', '<=', $now);
                })
                ->where(function ($query) use ($now) {
                    $query->whereNull('products.flash_sale_end_at')
                        ->orWhere('products.flash_sale_end_at', '>=', $now);
                })
                ->select(
                    'products.id',
                    'products.thumbnail',
                    'products.current_price',
                    'products.previous_price',
                    'products.stock',
                    'products.has_variants',
                    'products.flash_sale_price',
                    'products.flash_sale_start_at',
                    'products.flash_sale_end_at',
                    'product_contents.title',
                    'product_contents.summary',
                    'product_contents.slug',
                    'product_categories.name as category_name'
                )
                ->orderByRaw('CASE WHEN products.flash_sale_end_at IS NULL THEN 1 ELSE 0 END')
                ->orderBy('products.flash_sale_end_at')
                ->orderByDesc('products.id')
                ->limit(10)
                ->get();

            $flashSaleProducts = $this->hydrateProductCards($flashSaleProducts)
                ->map(function ($product) {
                    $oldPrice = (float) ($product->current_price ?? 0);
                    $salePrice = (float) ($product->flash_sale_price ?? $oldPrice);
                    $saveAmount = $oldPrice > $salePrice ? ($oldPrice - $salePrice) : 0;
                    $savePercent = ($oldPrice > 0 && $saveAmount > 0) ? (int) round(($saveAmount / $oldPrice) * 100) : 0;

                    $product->flash_sale_price = $salePrice;
                    $product->flash_sale_old_price = $oldPrice;
                    $product->flash_sale_save_amount = $saveAmount;
                    $product->flash_sale_save_percent = $savePercent;

                    $units = collect($product->quick_units ?? [])
                        ->map(function ($unit, $index) use ($salePrice, $oldPrice) {
                            if ($index === 0) {
                                $unit['price'] = $salePrice;
                                $unit['oldPrice'] = $oldPrice;
                            }

                            return $unit;
                        })
                        ->values()
                        ->all();

                    if (!empty($units)) {
                        $product->quick_units = $units;
                    }

                    return $product;
                })
                ->values();

            $nextEndingAt = $flashSaleProducts
                ->pluck('flash_sale_end_at')
                ->filter()
                ->map(function ($dateTime) {
                    return \Illuminate\Support\Carbon::parse($dateTime);
                })
                ->sortBy(function ($dateTime) {
                    return $dateTime->timestamp;
                })
                ->first();

            if (!empty($nextEndingAt) && $nextEndingAt->greaterThan($now)) {
                $flashSaleCountdownSeconds = $now->diffInSeconds($nextEndingAt);
            }
        }

        $flashSaleFeaturedProduct = $flashSaleProducts->first();

        $data['featuredProducts'] = $this->mapFeaturedProductsForView($featuredProducts);
        $data['flashSaleProducts'] = $flashSaleProducts;
        $data['flashSaleFeaturedProduct'] = $flashSaleFeaturedProduct;
        $data['flashSaleCountdownSeconds'] = $flashSaleCountdownSeconds;
        $data['homeCategories'] = $this->getHomeCategories($languageId);
        $data['serverFeaturedProducts'] = $this->buildQuickViewPayload($featuredProducts);
        $data['serverFlashSaleProducts'] = $this->buildQuickViewPayload($flashSaleProducts, true);
        $data['flashSaleDeal'] = $this->buildFlashSaleDealData($flashSaleFeaturedProduct, $flashSaleCountdownSeconds);

        return view('front.home.index', $data);
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function productDetails(Request $request)
    {
        // Preserve existing client-side behavior for links like product-details.html?id=some-id
        if (!$request->has('product') && $request->filled('id')) {
            return view('front.home.product-details');
        }

        $languageId = $this->getCurrentLanguageId();
        $productId = (int) $request->query('product', 0);

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

    private function hasFlashSaleColumns(): bool
    {
        return Schema::hasColumn('products', 'flash_sale_status')
            && Schema::hasColumn('products', 'flash_sale_price')
            && Schema::hasColumn('products', 'flash_sale_start_at')
            && Schema::hasColumn('products', 'flash_sale_end_at');
    }

    private function hydrateProductCards($products)
    {
        $productIds = collect($products)->pluck('id')->filter()->values();
        $sliderImagesByProduct = collect();
        $variantsByProduct = collect();
        $variantOptionRowsByVariant = collect();

        if ($productIds->isNotEmpty()) {
            $sliderImagesByProduct = DB::table('slider_images')
                ->where('item_type', 'product')
                ->whereIn('item_id', $productIds)
                ->select('item_id', 'image')
                ->orderBy('id')
                ->get()
                ->groupBy('item_id');

            $variantRows = DB::table('product_variants')
                ->whereIn('product_id', $productIds)
                ->where('status', 1)
                ->select('id', 'product_id', 'price')
                ->orderBy('id')
                ->get();

            $variantsByProduct = $variantRows->groupBy('product_id');
            $variantIds = $variantRows->pluck('id')->filter()->values();

            if ($variantIds->isNotEmpty()) {
                $variantOptionRowsByVariant = DB::table('product_variant_values as pvv')
                    ->join('product_option_values as pov', 'pov.id', '=', 'pvv.option_value_id')
                    ->join('product_options as po', 'po.id', '=', 'pov.product_option_id')
                    ->whereIn('pvv.variant_id', $variantIds)
                    ->select(
                        'pvv.variant_id',
                        'po.name as option_name',
                        'pov.value as option_value',
                        'po.position',
                        'pov.position as option_value_position'
                    )
                    ->orderBy('po.position')
                    ->orderBy('pov.position')
                    ->get()
                    ->groupBy('variant_id');
            }
        }

        return collect($products)->map(function ($product) use ($sliderImagesByProduct, $variantsByProduct, $variantOptionRowsByVariant) {
            $images = collect();

            if (!empty($product->thumbnail)) {
                $images->push(asset('assets/img/product/' . $product->thumbnail));
            }

            $galleryRows = $sliderImagesByProduct->get($product->id, collect());
            foreach ($galleryRows as $row) {
                if (!empty($row->image)) {
                    $images->push(asset('assets/img/product/gallery/' . $row->image));
                }
            }

            $product->images = $images->unique()->values()->all();
            $product->quick_units = [
                [
                    'label' => '1 unit',
                    'price' => (float) ($product->current_price ?? 0),
                    'oldPrice' => (float) ($product->previous_price ?? 0),
                ],
            ];

            if ((int) ($product->has_variants ?? 0) === 1) {
                $productVariants = $variantsByProduct->get($product->id, collect());

                if ($productVariants->isNotEmpty()) {
                    $units = $productVariants
                        ->values()
                        ->map(function ($variant, $index) use ($variantOptionRowsByVariant, $product) {
                            $optionRows = collect($variantOptionRowsByVariant->get($variant->id, collect()));

                            $labelParts = $optionRows
                                ->map(function ($row) {
                                    $name = trim((string) ($row->option_name ?? ''));
                                    $value = trim((string) ($row->option_value ?? ''));

                                    if ($name === '' || $value === '') {
                                        return null;
                                    }

                                    return $name . ': ' . $value;
                                })
                                ->filter()
                                ->values();

                            return [
                                'label' => $labelParts->isNotEmpty() ? $labelParts->implode(', ') : ('Option ' . ($index + 1)),
                                'price' => (float) ($variant->price ?? $product->current_price ?? 0),
                                'oldPrice' => (float) ($product->previous_price ?? 0),
                            ];
                        })
                        ->filter(function ($unit) {
                            return !empty($unit['label']);
                        })
                        ->values()
                        ->all();

                    if (!empty($units)) {
                        $product->quick_units = $units;
                    }
                }
            }

            return $product;
        });
    }

    private function mapFeaturedProductsForView($products)
    {
        return collect($products)->map(function ($product) {
            $title = $product->title ?: 'Untitled Product';
            $category = $product->category_name ?: 'Featured';
            $price = (float) ($product->current_price ?? 0);
            $oldPrice = (float) ($product->previous_price ?? 0);
            $showOldPrice = $oldPrice > $price && $oldPrice > 0;
            $discount = $showOldPrice ? (int) round((1 - ($price / $oldPrice)) * 100) : 0;
            $stock = (int) ($product->stock ?? 0);
            $hasVariants = (int) ($product->has_variants ?? 0) === 1;
            $images = collect($product->images ?? [])
                ->filter(function ($image) {
                    return is_string($image) && trim($image) !== '';
                })
                ->values()
                ->all();
            $thumbnailUrl = !empty($product->thumbnail)
                ? asset('assets/img/product/' . $product->thumbnail)
                : ($images[0] ?? null);

            if (empty($thumbnailUrl)) {
                return null;
            }

            $product->display_title = $title;
            $product->display_title_short = Str::limit($title, 42);
            $product->thumbnail_url = $thumbnailUrl;
            $product->badge_label = $category ?: 'Fresh';
            $product->badge_label_short = Str::limit($product->badge_label, 14);
            $product->price_value = $price;
            $product->old_price_value = $oldPrice;
            $product->show_old_price = $showOldPrice;
            $product->discount_percent = $discount;
            $product->stock_label = $stock > 0 ? 'Only ' . min($stock, 5) . ' left' : 'Out of stock';
            $product->variant_label = $hasVariants ? 'Variants' : 'Standard';
            $product->variant_text = $hasVariants ? 'Multiple sizes available' : 'Single size';

            return $product;
        })->filter()->values();
    }

    private function buildQuickViewPayload($products, bool $isFlashSale = false)
    {
        return collect($products)
            ->map(function ($product) use ($isFlashSale) {
                $title = $product->title ?: 'Untitled Product';
                $category = $product->category_name ?: 'Featured';
                $price = $isFlashSale
                    ? (float) ($product->flash_sale_price ?? ($product->current_price ?? 0))
                    : (float) ($product->current_price ?? 0);
                $oldPrice = $isFlashSale
                    ? (float) ($product->flash_sale_old_price ?? ($product->current_price ?? 0))
                    : (float) ($product->previous_price ?? 0);
                $summary = !empty($product->summary)
                    ? Str::limit(strip_tags($product->summary), 180)
                    : ($isFlashSale
                        ? 'Limited flash offer selected by our team.'
                        : 'Freshly selected item from our featured collection.');
                $images = collect($product->images ?? [])
                    ->filter(function ($image) {
                        return is_string($image) && trim($image) !== '';
                    })
                    ->values()
                    ->all();
                $thumbnail = !empty($product->thumbnail)
                    ? asset('assets/img/product/' . $product->thumbnail)
                    : ($images[0] ?? null);
                if (empty($images) && !empty($thumbnail)) {
                    $images[] = $thumbnail;
                }
                if (empty($images)) {
                    return null;
                }

                $units = is_array($product->quick_units ?? null) ? $product->quick_units : [];
                if (empty($units)) {
                    $units[] = [
                        'label' => '1 unit',
                        'price' => $price,
                        'oldPrice' => $oldPrice,
                    ];
                }

                return [
                    'id' => (string) $product->id,
                    'name' => $title,
                    'category' => $category,
                    'rating' => 4.7,
                    'reviews' => 142,
                    'badge' => $category,
                    'image' => $images[0],
                    'images' => $images,
                    'description' => $summary,
                    'nutrition' => ['Fresh stock', 'Quality checked', 'Fast delivery', 'Secure packaging'],
                    'reviewList' => [
                        ['name' => 'Ariana', 'rating' => 5, 'text' => 'Great quality and fast delivery.'],
                        ['name' => 'Chris', 'rating' => 4, 'text' => 'Loved the packaging and freshness.'],
                    ],
                    'units' => $units,
                    'isDeal' => $isFlashSale || ($oldPrice > $price),
                    'popular' => true,
                ];
            })
            ->filter()
            ->values();
    }

    private function buildFlashSaleDealData($dealFeatured, int $countdownSeconds): ?object
    {
        if (empty($dealFeatured)) {
            return null;
        }

        $title = $dealFeatured?->title ?: 'Flash sale item';
        $summary = !empty($dealFeatured?->summary)
            ? Str::limit(strip_tags($dealFeatured->summary), 120)
            : 'Limited-time offer available while stock lasts.';
        $salePrice = (float) ($dealFeatured?->flash_sale_price ?? 18.9);
        $oldPrice = (float) ($dealFeatured?->flash_sale_old_price ?? 23.5);
        $saveAmount = max(0, $oldPrice - $salePrice);
        $savePercent = ($oldPrice > 0 && $saveAmount > 0) ? (int) round(($saveAmount / $oldPrice) * 100) : 0;
        $images = collect($dealFeatured?->images ?? [])
            ->filter(function ($image) {
                return is_string($image) && trim($image) !== '';
            })
            ->values()
            ->all();
        $image = $images[0] ?? (!empty($dealFeatured?->thumbnail) ? asset('assets/img/product/' . $dealFeatured->thumbnail) : null);
        if (empty($image)) {
            return null;
        }
        $stock = (int) ($dealFeatured?->stock ?? 5);
        $stockLabel = $stock > 0 ? 'Only ' . $stock . ' left' : 'Out of stock';
        $detailsUrl = !empty($dealFeatured?->id)
            ? route('frontend.product.details', ['product' => $dealFeatured->id])
            : 'products.html';

        return (object) [
            'title' => $title,
            'summary' => $summary,
            'sale_price' => $salePrice,
            'old_price' => $oldPrice,
            'save_amount' => $saveAmount,
            'save_percent' => $savePercent,
            'image' => $image,
            'stock_label' => $stockLabel,
            'details_url' => $detailsUrl,
            'countdown_seconds' => $countdownSeconds,
        ];
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

    private function getHomeCategories(?int $languageId)
    {
        $hasIconColumn = Schema::hasColumn('product_categories', 'icon');
        $categoryColumns = ['id', 'name', 'slug'];

        if ($hasIconColumn) {
            $categoryColumns[] = 'icon';
        }

        $categories = ProductCategory::query()
            ->where('status', 1)
            ->when($languageId, function ($query) use ($languageId) {
                $query->where('language_id', $languageId);
            })
            ->orderByDesc('serial_number')
            ->limit(12)
            ->get($categoryColumns);

        if ($categories->isEmpty()) {
            return collect();
        }

        $categoryIds = $categories->pluck('id')->values();

        $counts = ProductContent::query()
            ->join('products', 'products.id', '=', 'product_contents.product_id')
            ->whereIn('product_contents.category_id', $categoryIds)
            ->when($languageId, function ($query) use ($languageId) {
                $query->where('product_contents.language_id', $languageId);
            })
            ->where('products.status', 1)
            ->select('product_contents.category_id', DB::raw('COUNT(DISTINCT product_contents.product_id) as total_items'))
            ->groupBy('product_contents.category_id')
            ->pluck('total_items', 'product_contents.category_id');

        $iconSet = $this->categoryIconSet();

        return $categories->values()->map(function ($category, $index) use ($counts, $iconSet, $hasIconColumn) {
            $normalized = strtolower(trim((string) $category->name));
            $subtitle = 'Fresh picks';

            if (str_contains($normalized, 'meat')) {
                $subtitle = 'Premium cuts';
            } elseif (str_contains($normalized, 'fruit')) {
                $subtitle = 'Sweet & juicy';
            } elseif (str_contains($normalized, 'fast') || str_contains($normalized, 'snack')) {
                $subtitle = 'Ready to eat';
            } elseif (str_contains($normalized, 'spice')) {
                $subtitle = 'Aroma & flavor';
            } elseif (str_contains($normalized, 'bread') || str_contains($normalized, 'bakery')) {
                $subtitle = 'Daily staples';
            } elseif (str_contains($normalized, 'vegetable')) {
                $subtitle = 'Greens & roots';
            } elseif (str_contains($normalized, 'cake') || str_contains($normalized, 'dessert')) {
                $subtitle = 'Sweet treats';
            }

            $category->subtitle = $subtitle;
            $category->item_count = (int) ($counts[$category->id] ?? 0);
            $category->icon_class = $hasIconColumn ? trim((string) ($category->icon ?? '')) : '';
            $category->icon_paths = $category->icon_class !== '' ? [] : $iconSet[$index % count($iconSet)];
            $category->display_name = Str::limit((string) $category->name, 20);

            return $category;
        });
    }

    private function categoryIconSet(): array
    {
        return [
            [
                'M4 14c0-4 3-7 7-7h3a6 6 0 0 1 0 12h-3a7 7 0 0 1-7-5Z',
                'M16 11a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z',
            ],
            [
                'M4 14c6-1 10-5 12-10 3 5 4 10 3 14-2 6-10 8-13 3-1-2-1-5-1-7Z',
                'M7 15c3-2 6-5 9-10',
            ],
            [
                'M4 9c0-2 4-4 8-4s8 2 8 4',
                'M4 13h16',
                'M6 17h12',
            ],
            [
                'M7 6h10v14H7z',
                'M9 4h6v2H9z',
                'M9 11h6',
            ],
            [
                'M4 12a4 4 0 0 1 4-4h8a4 4 0 0 1 0 8H8a4 4 0 0 1-4-4Z',
            ],
            [
                'M12 20V10',
                'M12 10c-3-3-6-3-8-3 0 4 3 6 8 6',
                'M12 10c3-3 6-3 8-3 0 4-3 6-8 6',
            ],
            [
                'M4 12h16v8H4z',
                'M6 9h12v3H6z',
                'M8 6h8v3H8z',
            ],
        ];
    }
}
