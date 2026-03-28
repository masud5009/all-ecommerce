@extends('front.layout')
@section('content')
    <!-- Hero Banner Slider -->
    <section class="premium-hero relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="hero-ambient-blob hero-ambient-blob-one"></div>
            <div class="hero-ambient-blob hero-ambient-blob-two"></div>
            <div class="hero-ambient-blob hero-ambient-blob-three"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <div class="relative hero-slider hero-slider-stage" data-hero-slider role="region" aria-roledescription="carousel"
                aria-label="Hero promotions" tabindex="0">
                <div class="hero-controls" aria-label="Hero slide controls">
                    <button type="button" class="hero-nav-btn" aria-label="Previous slide" data-prev>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M15 5l-7 7 7 7"></path>
                        </svg>
                    </button>
                    <button type="button" class="hero-nav-btn" aria-label="Next slide" data-next>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                @foreach ($homeSliders as $index => $slider)
                    <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-14 hero-slide is-active"
                        data-slide id="hero-slide-1" role="group" aria-roledescription="slide" aria-label="1 of 3"
                        aria-hidden="false">
                        <div class="hero-content">
                            <p
                                class="inline-flex items-center gap-2 rounded-full bg-emerald-100/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 hero-kicker">
                                {{ $slider->title }}
                            </p>
                            <h1
                                class="mt-5 text-4xl font-semibold leading-tight text-slate-900 sm:text-6xl sm:leading-tight lg:text-7xl lg:leading-[1.05] tracking-tight hero-title">
                                {{-- Groceries That Feel <span class="hero-gradient-text">Premium</span> Every Day --}}
                                {!! $slider->sub_title !!}
                            </h1>
                            <p class="mt-5 max-w-2xl text-base text-slate-600 sm:text-lg hero-text">
                                {!! $slider->description !!}
                            </p>
                            <div class="mt-7 flex flex-wrap items-center gap-3 hero-actions">
                                <a href="{{ $slider->button_url_1 }}"
                                    class="hero-btn-primary rounded-full px-8 py-4 text-sm font-semibold text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                    {{ $slider->button_text_1 }}
                                </a>
                                <a href="{{ $slider->button_url_2 }}"
                                    class="hero-btn-secondary rounded-full px-6 py-3.5 text-sm font-semibold text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                    {{ $slider->button_text_2 }}
                                </a>
                            </div>
                        </div>
                        <div class="relative hero-media">
                            <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
                            <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
                            <div class="hero-media-card group">
                                <img src="{{ asset('assets/img/home_slider/' . $slider->image) }}"
                                    srcset="{{ asset('assets/img/home_slider/' . $slider->image) }}"
                                    sizes="(min-width: 1024px) 520px, 100vw" width="700" height="520"
                                    alt="Fresh produce assortment on a market table"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    decoding="async" fetchpriority="high">
                                <div class="hero-media-gradient" aria-hidden="true"></div>
                            </div>
                            <div class="hero-media-badge hero-media-badge-top">
                                <p class="hero-media-badge-label">{{ $slider->image_left_badge_title }}</p>
                                <p class="hero-media-badge-value">{{ $slider->image_left_badge_sub_title }}</p>
                            </div>
                            <div class="hero-media-badge hero-media-badge-bottom">
                                <p class="hero-media-badge-label">{{ $slider->image_right_badge_title }}</p>
                                <p class="hero-media-badge-value">{{ $slider->image_right_badge_sub_title }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (count($homeSliders) > 1)
                    <div class="mt-10 flex items-center justify-center gap-3" aria-label="Choose slide">
                        <button
                            class="hero-dot bg-green-600 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                            type="button" aria-label="Go to slide 1" aria-controls="hero-slide-1" data-dot
                            aria-current="true"></button>
                        <button
                            class="hero-dot bg-green-200 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                            type="button" aria-label="Go to slide 2" aria-controls="hero-slide-2" data-dot
                            aria-current="false"></button>
                        <button
                            class="hero-dot bg-green-200 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                            type="button" aria-label="Go to slide 3" aria-controls="hero-slide-3" data-dot
                            aria-current="false"></button>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <div class="mx-auto mt-12 h-px max-w-5xl bg-gradient-to-r from-transparent via-green-200 to-transparent"></div>

    <!-- Categories section -->
    <section id="categories" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" aria-labelledby="category-heading"
        data-reveal>
        <div class="flex flex-wrap items-center justify-between gap-4" data-reveal-child>
            <div>
                <h2 id="category-heading" class="text-2xl font-semibold text-slate-900">
                    {{ @$sectionTitles->category_title ?? 'Categories section title' }}
                </h2>
            </div>
            <div class="hidden items-center gap-2 sm:flex">
                <button type="button" aria-label="Scroll categories previous" data-category-prev
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-green-100 bg-white text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M15 6l-6 6 6 6"></path>
                    </svg>
                </button>
                <button type="button" aria-label="Scroll categories next" data-category-next
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-green-100 bg-white text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M9 6l6 6-6 6"></path>
                    </svg>
                </button>
            </div>
        </div>
        <div class="category-scroll mt-8 flex gap-4 overflow-x-auto pb-4 scroll-px-4 scroll-smooth snap-x snap-mandatory"
            data-category-track>
            @forelse ($homeCategories as $category)
                <a href="{{ route('frontend.shop', ['category' => $category->id]) }}"
                    aria-label="Browse {{ $category->name }}" data-reveal-child
                    class="group flex min-w-[150px] flex-col items-center rounded-2xl border border-green-100 bg-white px-4 py-5 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-300 hover:bg-green-50/70 hover:shadow-[0_16px_32px_rgba(16,185,129,0.18)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white sm:min-w-[160px] lg:min-w-[170px] snap-start">
                    <div class="brush-ring flex h-20 w-20 items-center justify-center">
                        <span
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-green-100 transition duration-300 group-hover:scale-105">
                            @if (!empty($category->icon))
                                <i class="{{ $category->icon }} text-[26px] leading-none text-emerald-600"
                                    aria-hidden="true"></i>
                            @endif
                        </span>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-emerald-900 transition group-hover:text-green-800">
                        {{ $category->name }}
                    </p>
                    <span
                        class="mt-3 inline-flex items-center rounded-full border border-green-100 bg-green-50 px-2.5 py-1 text-[11px] font-semibold text-green-700">
                        {{ $category->productContent->count() }} {{ __('products') }}
                    </span>
                    <span
                        class="mt-3 inline-flex items-center gap-1 text-[11px] font-semibold text-green-700 opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100 translate-x-1"
                        aria-hidden="true">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path d="M9 6l6 6-6 6"></path>
                        </svg>
                    </span>
                </a>
            @empty
                <div class="w-full rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500"
                    data-reveal-child>
                    {{ __('NO FEATURED CATEGORY FOUND!') }}
                </div>
            @endforelse
        </div>
    </section>

    <!-- Featured products -->
    <section class="mx-auto mt-10 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div
            class="rounded-3xl border border-green-100 bg-gradient-to-br from-green-50/70 via-white to-emerald-50/60 p-6 shadow-sm sm:p-8">
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal-child>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-700">
                        {{ @$sectionTitles->featured_product_title ?? 'Featured products section title' }}
                    </p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ @$sectionTitles->featured_product_sub_title ?? 'Featured products section sub title' }}
                    </h2>
                </div>
                <a href="{{ route('frontend.shop') }}"
                    class="inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:border-green-600 hover:bg-green-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2">
                    {{ __('View all products') }}
                </a>
            </div>

            @if (count($featuredProducts) > 0)
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($featuredProducts as $product)
                        @include('front.home.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            @else
                <div class="mt-8 rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500"
                    data-reveal-child>
                    {{ __('NO FEATURED PRODUCTS FOUND!') }}
                </div>
            @endif
        </div>
    </section>


    <!-- Popular products section -->
    <section class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="flex flex-wrap items-end justify-between gap-4" data-reveal-child>
            <div>
                <h2 class="text-2xl font-semibold text-slate-900">
                    {{ @$sectionTitles->popular_product_title ?? 'Popular products section title' }}
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    {{ @$sectionTitles->popular_product_sub_title ?? 'Popular products section sub title' }}
                </p>
            </div>
            <a href="{{ route('frontend.shop') }}" class="text-sm font-semibold text-green-700">
                {{ __('Shop all') }}</a>
        </div>
        @if (count($popularProducts) > 0)
            <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($popularProducts as $product)
                    @include('front.home.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500"
                data-reveal-child>
                {{ __('NO PRODUCT FOUND!') }}
            </div>
        @endif
    </section>

    <div class="mx-auto mt-16 h-px max-w-5xl bg-gradient-to-r from-transparent via-green-200 to-transparent"></div>

    @if (count($flashSaleCardProducts) > 0)
        <section id="deals" class="flash-sale-section mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
            <div class="flash-sale-head flex flex-wrap items-end justify-between gap-4" data-reveal-child>
                <div>
                    <p class="flash-sale-kicker text-xs font-semibold uppercase tracking-wide text-green-700">
                        {{ @$sectionTitles->flash_title ?? 'Flash sale section title' }}
                    </p>
                    <h2 class="flash-sale-title mt-2 text-2xl font-semibold text-slate-900">
                        {{ @$sectionTitles->flash_sub_title ?? 'Flash sale section sub title' }}
                    </h2>
                </div>
                <div class="flash-sale-head-actions flex items-center gap-3" data-reveal-child>
                    <a href="{{ route('frontend.shop') }}"
                        class="flash-sale-all-deals inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:border-green-600 hover:bg-green-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2">
                        {{ __('Show more products') }}
                    </a>
                </div>
            </div>
            <div class="flash-sale-shell mt-8 rounded-3xl border border-green-100 bg-gradient-to-br from-green-50/80 via-white to-emerald-50/60 p-6 shadow-sm sm:p-8"
                data-reveal-child>
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($flashSaleCardProducts as $product)
                        @php
                            $content = $product->content->first();
                            $cardTitle = $content->title ?? ('Product #' . $product->id);
                            $cardSummaryRaw = $content->summary ?? $content->description ?? 'Limited time offer on selected items.';
                            $cardSummary = trim(preg_replace('/\s+/', ' ', strip_tags((string) $cardSummaryRaw)));
                            $discountPercent = min(max((float) ($product->flash_sale_price ?? 0), 0), 100);
                            $variations = $product->variations ?? collect();

                            if ((int) ($product->has_variants ?? 0) === 1 && $variations->isNotEmpty()) {
                                $variantPrices = $variations->map(function ($variation) use ($product) {
                                    return (float) ($variation->price ?? $product->current_price ?? 0);
                                })->values();

                                $oldMinPrice = (float) ($variantPrices->min() ?? 0);
                                $oldMaxPrice = (float) ($variantPrices->max() ?? 0);
                                $saleMinPrice = max($oldMinPrice * (1 - ($discountPercent / 100)), 0);
                                $saleMaxPrice = max($oldMaxPrice * (1 - ($discountPercent / 100)), 0);

                                $salePriceLabel = $saleMinPrice === $saleMaxPrice
                                    ? currency_symbol($saleMinPrice)
                                    : currency_symbol($saleMinPrice) . ' - ' . currency_symbol($saleMaxPrice);

                                $oldPriceLabel = $oldMinPrice === $oldMaxPrice
                                    ? currency_symbol($oldMinPrice)
                                    : currency_symbol($oldMinPrice) . ' - ' . currency_symbol($oldMaxPrice);

                                $savePercent = round($discountPercent);
                            } else {
                                $currentPrice = (float) ($product->current_price ?? 0);
                                $salePrice = max($currentPrice * (1 - ($discountPercent / 100)), 0);
                                $oldPrice = $currentPrice;
                                $saveAmount = max($oldPrice - $salePrice, 0);
                                $savePercent = $oldPrice > 0 ? round(($saveAmount / $oldPrice) * 100) : 0;
                                $salePriceLabel = currency_symbol($salePrice);
                                $oldPriceLabel = currency_symbol($oldPrice);
                            }
                            $stockLabel = ($product->stock ?? 0) > 0 ? __('In Stock') : __('Stock Out');
                            $image = !empty($product->thumbnail) ? asset('assets/img/product/' . $product->thumbnail) : '';
                            $countdownSeconds = 0;
                            if (!empty($product->flash_sale_end_at)) {
                                $countdownSeconds = max(0, now()->diffInSeconds(\Carbon\Carbon::parse($product->flash_sale_end_at), false));
                            }
                        @endphp
                        <a href="{{ route('frontend.shop.details', ['id' => $product->id]) }}"
                            class="flash-sale-featured-card relative block overflow-hidden rounded-3xl border border-green-100 bg-white/90 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2"
                            data-reveal-child>
                            <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-green-100/80 blur-2xl"
                                aria-hidden="true"></div>
                            <div class="pointer-events-none absolute -bottom-12 left-6 h-24 w-24 rounded-full bg-emerald-100/60 blur-2xl"
                                aria-hidden="true"></div>

                            <div class="relative flex items-center justify-between">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full bg-green-600 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white">
                                    <i class="fas fa-bolt text-[10px]" aria-hidden="true"></i>
                                    Flash
                                </span>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex items-center gap-1 rounded-full border border-green-100 bg-white px-2.5 py-1 text-[11px] font-semibold text-slate-600">
                                        <svg class="h-3.5 w-3.5 text-green-600" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <path d="M12 7v6l3 2"></path>
                                        </svg>
                                        <span data-countdown data-countdown-seconds="{{ $countdownSeconds }}">02:15:32</span>
                                    </span>
                                    <span class="text-xs text-slate-500">{{ $stockLabel }}</span>
                                </div>
                            </div>

                            <h3 class="relative mt-4 text-2xl font-semibold text-slate-900">{{ $cardTitle }}</h3>
                            <p class="relative mt-2 text-sm text-slate-600">{{ truncateString($cardSummary,100) }}</p>

                            <div class="relative mt-4 flex items-end gap-3">
                                <span class="text-2xl font-semibold text-slate-900">{{ $salePriceLabel }}</span>
                                @if (!empty($oldPriceLabel) && $oldPriceLabel !== $salePriceLabel)
                                    <span class="text-sm text-slate-400 line-through">{{ $oldPriceLabel }}</span>
                                @endif
                            </div>

                            <div class="relative mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                                <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                                    {{ $savePercent > 0 ? 'Save ' . $savePercent . '%' : 'Limited offer' }}
                                </span>
                                <span class="rounded-full border border-green-100 px-3 py-1">Free delivery</span>
                                <span class="rounded-full border border-green-100 px-3 py-1 text-green-700">{{ $stockLabel }}</span>
                            </div>

                            @if (!empty($image))
                                <div class="relative mt-6 overflow-hidden rounded-2xl">
                                    <img src="{{ $image }}" alt="{{ $cardTitle }}" class="h-48 w-full object-cover">
                                </div>
                            @endif

                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- <section id="rewards" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div
            class="relative overflow-hidden rounded-3xl border border-green-100 bg-gradient-to-r from-green-600 to-emerald-500 p-8 text-white shadow-lg">
            <div class="pointer-events-none absolute -right-16 -top-10 h-40 w-40 rounded-full bg-white/20 blur-3xl"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute bottom-0 left-8 h-32 w-32 rounded-full bg-white/10 blur-3xl"
                aria-hidden="true"></div>
            <div class="relative grid gap-8 lg:grid-cols-[1.3fr_0.9fr] lg:items-center">
                <div data-reveal-child>
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/80">Loyalty banner</p>
                    <h2 class="mt-3 text-3xl font-semibold">FreshCart Rewards is here.</h2>
                    <p class="mt-3 text-sm text-white/80">Earn points on every order, unlock member-only bundles,
                        and access
                        priority delivery slots.</p>
                    <div class="mt-6 grid gap-3 text-sm text-white/90">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white/20">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" aria-hidden="true">
                                    <path d="M12 3v18"></path>
                                    <path d="M8 7h7a3 3 0 0 1 0 6H9a3 3 0 0 0 0 6h7"></path>
                                </svg>
                            </span>
                            <span>Earn points on every order, automatically.</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white/20">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" aria-hidden="true">
                                    <path d="M3 12h4l2 4 4-8 2 4h6"></path>
                                </svg>
                            </span>
                            <span>Unlock member-only bundles and early drops.</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-2xl bg-white/20">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" aria-hidden="true">
                                    <path d="M12 3c4 3 6 6 6 9 0 4-3 7-6 9-3-2-6-5-6-9 0-3 2-6 6-9Z"></path>
                                    <path d="M12 7v10"></path>
                                </svg>
                            </span>
                            <span>Priority delivery slots when you need them.</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-white">You're 30% away from free delivery</p>
                        <div class="mt-2 h-2 w-full rounded-full bg-white/20" role="progressbar" aria-valuenow="70"
                            aria-valuemin="0" aria-valuemax="100">
                            <div class="h-2 w-[70%] rounded-full bg-white"></div>
                        </div>
                    </div>
                </div>
                <div class="rounded-2xl bg-white/15 p-6" data-reveal-child>
                    <p class="text-xs font-semibold uppercase tracking-wide text-white/70">Rewards snapshot</p>
                    <p class="mt-3 text-4xl font-semibold">1 point</p>
                    <p class="text-sm text-white/80">per $1 spent</p>
                    <p class="mt-4 text-sm text-white/80">Redeem 200 points for $10 off your next order.</p>
                    <a href="{{ route('frontend.shop') }}"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">Join
                        Free</a>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- <div class="mx-auto mt-16 h-px max-w-5xl bg-gradient-to-r from-transparent via-green-200 to-transparent"></div> --}}

    <section class="mx-auto mb-16 mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="relative overflow-hidden rounded-3xl border border-green-100 bg-white p-8 shadow-sm sm:p-10">
            <div class="pointer-events-none absolute -left-20 top-10 h-44 w-44 rounded-full bg-green-100/60 blur-3xl"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-16 bottom-8 h-36 w-36 rounded-full bg-emerald-100/60 blur-3xl"
                aria-hidden="true"></div>
            <div class="relative">
                <div class="text-center" data-reveal-child>
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-700">
                        {{ @$sectionTitles->features_title ?? 'Freshness section title' }}
                    </p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-900">
                        {{ @$sectionTitles->features_subtitle ?? 'Freshness section sub title' }}
                    </h2>
                    <p class="mt-3 text-sm text-slate-600">
                        {{ @$sectionTitles->features_text ?? 'Freshness section description' }}
                    </p>
                </div>
                <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_auto_1fr] lg:items-center">
                    <div class="order-2 space-y-6 lg:order-none" data-reveal-child>
                        @forelse ($freshnessLeftItems as $item)
                            <div class="flex items-start gap-4">
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                    @if (!empty($item->icon))
                                        <i class="{{ $item->icon }} text-lg"></i>
                                    @else
                                        <i class="fas fa-seedling text-lg"></i>
                                    @endif
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item->title }}</p>
                                    <p class="mt-1 text-xs text-slate-600">{{ $item->text }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-green-200 bg-white p-6 text-sm text-slate-500">
                                {{ __('No left side feature items found.') }}
                            </div>
                        @endforelse
                    </div>
                    <div class="order-1 mx-auto flex w-64 items-center justify-center sm:w-72 lg:order-none lg:w-80"
                        data-reveal-child>
                        <div class="relative">
                            <div
                                class="absolute inset-0 rounded-full bg-green-50 shadow-[0_25px_80px_rgba(16,185,129,0.2)]">
                            </div>
                            <img src="{{ !empty($sectionTitles?->features_image) ? asset('assets/img/home_section/' . $sectionTitles->features_image) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=640&q=80' }}"
                                alt="Fresh vegetables assortment"
                                class="relative h-auto w-full object-contain drop-shadow-2xl" loading="lazy"
                                width="420" height="420">
                        </div>
                    </div>
                    <div class="order-3 space-y-6 lg:order-none" data-reveal-child>
                        @forelse ($freshnessRightItems as $item)
                            <div class="flex items-start gap-4">
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                    @if (!empty($item->icon))
                                        <i class="{{ $item->icon }} text-lg"></i>
                                    @else
                                        <i class="fas fa-seedling text-lg"></i>
                                    @endif
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item->title }}</p>
                                    <p class="mt-1 text-xs text-slate-600">{{ $item->text }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-green-200 bg-white p-6 text-sm text-slate-500">
                                {{ __('No right side feature items found.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
