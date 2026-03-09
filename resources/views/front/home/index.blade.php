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

                <!-- Slide 1 -->
                <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-14 hero-slide is-active" data-slide
                    id="hero-slide-1" role="group" aria-roledescription="slide" aria-label="1 of 3" aria-hidden="false">
                    <div class="hero-content">
                        <p
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-100/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 hero-kicker">
                            Exclusive launch week
                        </p>
                        <h1
                            class="mt-5 text-4xl font-semibold leading-tight text-slate-900 sm:text-6xl sm:leading-tight lg:text-7xl lg:leading-[1.05] tracking-tight hero-title">
                            Groceries That Feel <span class="hero-gradient-text">Premium</span> Every Day
                        </h1>
                        <p class="mt-5 max-w-2xl text-base text-slate-600 sm:text-lg hero-text">
                            Chef grade produce, artisanal pantry staples, and dairy delivered with white-glove
                            care in under 90 minutes.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center gap-3 hero-actions">
                            <a href="products.html"
                                class="hero-btn-primary rounded-full px-8 py-4 text-sm font-semibold text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                Start Shopping
                            </a>
                            <a href="#deals"
                                class="hero-btn-secondary rounded-full px-6 py-3.5 text-sm font-semibold text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                View Deals
                            </a>
                        </div>
                    </div>
                    <div class="relative hero-media">
                        <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
                        <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
                        <div class="hero-media-card group">
                            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80"
                                srcset="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=640&q=80 640w, https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=900&q=80 900w, https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=80 1200w"
                                sizes="(min-width: 1024px) 520px, 100vw" width="700" height="520"
                                alt="Fresh produce assortment on a market table"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                decoding="async" fetchpriority="high">
                            <div class="hero-media-gradient" aria-hidden="true"></div>
                        </div>
                        <div class="hero-media-badge hero-media-badge-top">
                            <p class="hero-media-badge-label">Curated Daily</p>
                            <p class="hero-media-badge-value">Market fresh produce</p>
                        </div>
                        <div class="hero-media-badge hero-media-badge-bottom">
                            <p class="hero-media-badge-label">Next Slot</p>
                            <p class="hero-media-badge-value">7:30 PM delivery</p>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="hidden grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-14 hero-slide" data-slide
                    id="hero-slide-2" role="group" aria-roledescription="slide" aria-label="2 of 3" aria-hidden="true">
                    <div class="hero-content">
                        <p
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-100/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 hero-kicker">
                            Fast lane delivery
                        </p>
                        <h2
                            class="mt-5 text-4xl font-semibold leading-tight text-slate-900 sm:text-6xl sm:leading-tight lg:text-7xl lg:leading-[1.05] tracking-tight hero-title">
                            Farm to Fridge With <span class="hero-gradient-text">Live Tracking</span>
                        </h2>
                        <p class="mt-5 max-w-2xl text-base text-slate-600 sm:text-lg hero-text">
                            Keep your schedule smooth with precise ETA updates, temperature-safe transport, and
                            dedicated rider support.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center gap-3 hero-actions">
                            <a href="products.html"
                                class="hero-btn-primary rounded-full px-8 py-4 text-sm font-semibold text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                Shop Fresh
                            </a>
                            <a href="#deals"
                                class="hero-btn-secondary rounded-full px-6 py-3.5 text-sm font-semibold text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                Explore Weekly Deals
                            </a>
                        </div>
                    </div>
                    <div class="relative hero-media">
                        <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
                        <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
                        <div class="hero-media-card group">
                            <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80"
                                srcset="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=640&q=80 640w, https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80 900w, https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80 1200w"
                                sizes="(min-width: 1024px) 520px, 100vw" width="700" height="520"
                                alt="Healthy salad bowl with fresh vegetables"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                loading="lazy" decoding="async">
                            <div class="hero-media-gradient" aria-hidden="true"></div>
                        </div>
                        <div class="hero-media-badge hero-media-badge-top">
                            <p class="hero-media-badge-label">Transport Quality</p>
                            <p class="hero-media-badge-value">Temperature controlled</p>
                        </div>
                        <div class="hero-media-badge hero-media-badge-bottom">
                            <p class="hero-media-badge-label">Delivery ETA</p>
                            <p class="hero-media-badge-value">Expected in 42 minutes</p>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="hidden grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-14 hero-slide" data-slide
                    id="hero-slide-3" role="group" aria-roledescription="slide" aria-label="3 of 3"
                    aria-hidden="true">
                    <div class="hero-content">
                        <p
                            class="inline-flex items-center gap-2 rounded-full bg-emerald-100/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 hero-kicker">
                            Pantry signature edit
                        </p>
                        <h2
                            class="mt-5 text-4xl font-semibold leading-tight text-slate-900 sm:text-6xl sm:leading-tight lg:text-7xl lg:leading-[1.05] tracking-tight hero-title">
                            Stock Smart With <span class="hero-gradient-text">Chef Picked</span> Essentials
                        </h2>
                        <p class="mt-5 max-w-2xl text-base text-slate-600 sm:text-lg hero-text">
                            Build a premium pantry in minutes with curated weekly bundles, transparent savings, and
                            easy returns if something misses the mark.
                        </p>
                        <div class="mt-7 flex flex-wrap items-center gap-3 hero-actions">
                            <a href="products.html"
                                class="hero-btn-primary rounded-full px-8 py-4 text-sm font-semibold text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                Build My Basket
                            </a>
                            <a href="#deals"
                                class="hero-btn-secondary rounded-full px-6 py-3.5 text-sm font-semibold text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                Compare Bundles
                            </a>
                        </div>
                    </div>
                    <div class="relative hero-media">
                        <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
                        <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
                        <div class="hero-media-card group">
                            <img src="https://images.unsplash.com/photo-1506807803488-8eafc15323d1?auto=format&fit=crop&w=1200&q=80"
                                srcset="https://images.unsplash.com/photo-1506807803488-8eafc15323d1?auto=format&fit=crop&w=640&q=80 640w, https://images.unsplash.com/photo-1506807803488-8eafc15323d1?auto=format&fit=crop&w=900&q=80 900w, https://images.unsplash.com/photo-1506807803488-8eafc15323d1?auto=format&fit=crop&w=1200&q=80 1200w"
                                sizes="(min-width: 1024px) 520px, 100vw" width="700" height="520"
                                alt="Fresh citrus fruit in a wooden crate"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                loading="lazy" decoding="async">
                            <div class="hero-media-gradient" aria-hidden="true"></div>
                        </div>
                        <div class="hero-media-badge hero-media-badge-top">
                            <p class="hero-media-badge-label">Top Rated Bundle</p>
                            <p class="hero-media-badge-value">Signature pantry box</p>
                        </div>
                        <div class="hero-media-badge hero-media-badge-bottom">
                            <p class="hero-media-badge-label">Bundle Savings</p>
                            <p class="hero-media-badge-value">Save up to 18 percent</p>
                        </div>
                    </div>
                </div>

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
            </div>
        </div>
    </section>

    <div class="mx-auto mt-12 h-px max-w-5xl bg-gradient-to-r from-transparent via-green-200 to-transparent"></div>

    <!-- Categories section -->
    <section id="categories" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" aria-labelledby="category-heading"
        data-reveal>
        <div class="flex flex-wrap items-center justify-between gap-4" data-reveal-child>
            <div>
                <h2 id="category-heading" class="text-3xl font-semibold text-slate-900">Browse By Categories</h2>
                <p class="mt-2 text-sm text-slate-600">Hand-picked aisles for quick grocery runs.</p>
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
                <a href="#" aria-label="Browse {{ $category->name }}" data-reveal-child
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
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Featured Products</p>
                    <h2 class="mt-2 text-3xl font-semibold text-slate-900">Handpicked picks for today</h2>
                </div>
                <a href="products.html"
                    class="inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:border-green-600 hover:bg-green-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2">
                    View all products
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
                <h2 class="text-3xl font-semibold text-slate-900">Popular right now</h2>
                <p class="mt-2 text-sm text-slate-600">Most loved picks across produce, pantry, and seafood.</p>
            </div>
            <a href="products.html" class="text-sm font-semibold text-green-700">Shop all</a>
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
                No popular products found.
            </div>
        @endif
    </section>

    <div class="mx-auto mt-16 h-px max-w-5xl bg-gradient-to-r from-transparent via-green-200 to-transparent"></div>

    @if (count($flashSaleCardProducts) > 0)
        <section id="deals" class="flash-sale-section mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
            <div class="flash-sale-head flex flex-wrap items-end justify-between gap-4" data-reveal-child>
                <div>
                    <p class="flash-sale-kicker text-xs font-semibold uppercase tracking-wide text-green-700">Flash sale
                    </p>
                    <h2 class="flash-sale-title mt-2 text-3xl font-semibold text-slate-900">{{ $flashSaleDeal->title }}
                    </h2>
                </div>
                <div class="flash-sale-head-actions flex items-center gap-3" data-reveal-child>
                    <span
                        class="flash-sale-timer hidden items-center gap-2.5 rounded-full border border-green-100 bg-white px-4 py-2 text-sm text-slate-600 sm:inline-flex">
                        <svg class="h-4 w-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 7v6l3 2"></path>
                        </svg>
                        Ends in <span class="font-semibold tabular-nums text-slate-900" data-countdown
                            data-countdown-seconds="{{ $flashSaleDeal->countdown_seconds }}">02:15:32</span>
                    </span>
                    <a href="products.html"
                        class="flash-sale-all-deals inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:border-green-600 hover:bg-green-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2">
                        Show more products
                    </a>
                </div>
            </div>
            <div class="flash-sale-shell mt-8 rounded-3xl border border-green-100 bg-gradient-to-br from-green-50/80 via-white to-emerald-50/60 p-6 shadow-sm sm:p-8"
                data-reveal-child>
                <div class="grid gap-6 lg:grid-cols-[1.1fr_1.9fr]">
                    <div class="flash-sale-featured-card relative overflow-hidden rounded-3xl border border-green-100 bg-white/90 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                        data-reveal-child>
                        <div class="pointer-events-none absolute -right-10 -top-10 h-28 w-28 rounded-full bg-green-100/80 blur-2xl"
                            aria-hidden="true"></div>
                        <div class="pointer-events-none absolute -bottom-12 left-6 h-24 w-24 rounded-full bg-emerald-100/60 blur-2xl"
                            aria-hidden="true"></div>
                        <div class="relative flex items-center justify-between">
                            <span
                                class="rounded-full bg-green-600 px-3 py-1 text-[11px] font-semibold uppercase tracking-wide text-white">Featured</span>
                            <span class="text-xs text-slate-500">Ends in <span
                                    class="font-semibold tabular-nums text-slate-900" data-countdown
                                    data-countdown-seconds="{{ $flashSaleDeal->countdown_seconds }}">02:15:32</span></span>
                        </div>
                        <h3 class="relative mt-4 text-2xl font-semibold text-slate-900">{{ $flashSaleDeal->title }}</h3>
                        <p class="relative mt-2 text-sm text-slate-600">{{ $flashSaleDeal->summary }}</p>
                        <div class="relative mt-4 flex items-end gap-3">
                            <span
                                class="text-3xl font-semibold text-slate-900">{{ currency_symbol($flashSaleDeal->sale_price) }}</span>
                            @if ($flashSaleDeal->old_price > $flashSaleDeal->sale_price)
                                <span
                                    class="text-sm text-slate-400 line-through">{{ currency_symbol($flashSaleDeal->old_price) }}</span>
                            @endif
                        </div>
                        <div class="relative mt-4 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                            <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                                {{ $flashSaleDeal->save_percent > 0 ? 'Save ' . $flashSaleDeal->save_percent . '%' : 'Limited offer' }}
                            </span>
                            <span class="rounded-full border border-green-100 px-3 py-1">Free delivery</span>
                            <span
                                class="rounded-full border border-green-100 px-3 py-1 text-green-700">{{ $flashSaleDeal->stock_label }}</span>
                        </div>
                        <div class="relative mt-6">
                            <a href="{{ $flashSaleDeal->details_url }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">Grab
                                bundle</a>
                        </div>
                        <div class="relative mt-6 overflow-hidden rounded-2xl">
                            <img src="{{ $flashSaleDeal->image }}" alt="{{ $flashSaleDeal->title }}"
                                class="h-48 w-full object-cover">
                        </div>
                        <div class="relative mt-6 grid grid-cols-3 gap-3 text-xs text-slate-600">
                            <div class="rounded-2xl border border-green-100 bg-white p-3">
                                <p class="text-slate-500">Avg store</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ currency_symbol($flashSaleDeal->old_price) }}
                                </p>
                            </div>
                            <div class="rounded-2xl border border-green-100 bg-white p-3">
                                <p class="text-slate-500">FreshCart</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ currency_symbol($flashSaleDeal->sale_price) }}
                                </p>
                            </div>
                            <div class="rounded-2xl border border-green-100 bg-white p-3">
                                <p class="text-slate-500">You save</p>
                                <p class="mt-1 text-sm font-semibold text-green-700">
                                    {{ currency_symbol($flashSaleDeal->save_amount) }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="flash-sale-carousel-shell relative overflow-hidden rounded-3xl border border-green-100 bg-white p-5 shadow-sm"
                        data-reveal-child>
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">More curated deals</p>
                                <p class="mt-1 text-xs text-slate-500">Drag to explore or use arrows</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    class="flash-sale-arrow inline-flex h-10 w-10 items-center justify-center rounded-full border border-green-100 bg-white text-slate-500 transition hover:border-green-300 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-40"
                                    type="button" aria-label="Scroll deals left" data-deals-prev
                                    aria-controls="deals-carousel">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path d="M15 18l-6-6 6-6"></path>
                                    </svg>
                                </button>
                                <button
                                    class="flash-sale-arrow inline-flex h-10 w-10 items-center justify-center rounded-full border border-green-100 bg-white text-slate-500 transition hover:border-green-300 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-40"
                                    type="button" aria-label="Scroll deals right" data-deals-next
                                    aria-controls="deals-carousel">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" aria-hidden="true">
                                        <path d="M9 18l6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="relative mt-4">
                            <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-10 bg-gradient-to-r from-white to-transparent"
                                aria-hidden="true"></div>
                            <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-10 bg-gradient-to-l from-white to-transparent"
                                aria-hidden="true"></div>
                            <div class="flash-sale-slider scrollbar-hide flex gap-4 overflow-x-auto pb-4 pr-6 snap-x snap-mandatory scroll-px-4 cursor-grab active:cursor-grabbing"
                                data-deals-slider id="deals-carousel" role="region" aria-label="Today's deals carousel"
                                tabindex="0">
                                @foreach ($flashSaleCardProducts as $product)
                                    <div class="min-w-[220px] snap-start sm:min-w-[240px] lg:min-w-[260px]">
                                        @include('front.home.partials.product-card', [
                                            'product' => $product,
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                            <div class="flex items-center gap-2">
                                <span
                                    class="flash-sale-live-count inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-[11px] font-semibold text-green-700"
                                    data-deals-count>{{ !empty($flashSaleCardProducts) ? $flashSaleCardProducts->count() : 0 }}</span>
                                <span>items live now</span>
                            </div>
                            <span
                                class="flash-sale-swipe inline-flex items-center rounded-full border border-green-100 bg-white px-3 py-1">Swipe
                                &
                                discover</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section id="rewards" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
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
                    <a href="products.html"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white/70">Join
                        Free</a>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto mt-16 h-px max-w-5xl bg-gradient-to-r from-transparent via-green-200 to-transparent"></div>

    <section class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="relative overflow-hidden rounded-3xl border border-green-100 bg-white p-8 shadow-sm sm:p-10">
            <div class="pointer-events-none absolute -left-20 top-10 h-44 w-44 rounded-full bg-green-100/60 blur-3xl"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-16 bottom-8 h-36 w-36 rounded-full bg-emerald-100/60 blur-3xl"
                aria-hidden="true"></div>
            <div class="relative">
                <div class="text-center" data-reveal-child>
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Why FreshCart</p>
                    <h2 class="mt-3 text-3xl font-semibold text-slate-900">Freshness you can feel.</h2>
                    <p class="mt-3 text-sm text-slate-600">Hand-picked produce, secure payment, and fast
                        delivery—every time.
                    </p>
                </div>
                <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_auto_1fr] lg:items-center">
                    <div class="order-2 space-y-6 lg:order-none" data-reveal-child>
                        <div class="flex items-start gap-4">
                            <span
                                class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M3 7l9 5 9-5"></path>
                                    <path d="M3 7l9-4 9 4v10l-9 5-9-5z"></path>
                                    <path d="M12 12v10"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Handmade Products</p>
                                <p class="mt-1 text-xs text-slate-600">We collect fresh natural fruits for your
                                    healthy life.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <span
                                class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M12 7c-3 0-5 2-5 5 0 4 2 7 5 7s5-3 5-7c0-3-2-5-5-5Z"></path>
                                    <path d="M12 7c0-2 2-4 4-4"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Organic and Fresh</p>
                                <p class="mt-1 text-xs text-slate-600">Our products are 100% natural and fresh.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <span
                                class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M12 3c4 3 6 6 6 9 0 4-3 7-6 9-3-2-6-5-6-9 0-3 2-6 6-9Z"></path>
                                    <path d="M12 7v10"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">150+ Organic Items</p>
                                <p class="mt-1 text-xs text-slate-600">We stock 150+ organic food items for your
                                    pantry.</p>
                            </div>
                        </div>
                    </div>
                    <div class="order-1 mx-auto flex w-64 items-center justify-center sm:w-72 lg:order-none lg:w-80"
                        data-reveal-child>
                        <div class="relative">
                            <div
                                class="absolute inset-0 rounded-full bg-green-50 shadow-[0_25px_80px_rgba(16,185,129,0.2)]">
                            </div>
                            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=640&q=80"
                                alt="Fresh vegetables assortment"
                                class="relative h-auto w-full object-contain drop-shadow-2xl" loading="lazy"
                                width="420" height="420">
                        </div>
                    </div>
                    <div class="order-3 space-y-6 lg:order-none" data-reveal-child>
                        <div class="flex items-start gap-4">
                            <span
                                class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                                    <path d="M3 10h18"></path>
                                    <path d="M7 15h4"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">100% Secure Payment</p>
                                <p class="mt-1 text-xs text-slate-600">We make sure your payment method stays
                                    secure.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <span
                                class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M12 4v10"></path>
                                    <circle cx="12" cy="16" r="4"></circle>
                                    <path d="M12 8a2 2 0 0 0-2 2"></path>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Temperature Control</p>
                                <p class="mt-1 text-xs text-slate-600">We keep every item cool and fresh in
                                    transit.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <span
                                class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" aria-hidden="true">
                                    <path d="M3 16h8"></path>
                                    <path d="M13 16h4l3-3v-4h-7v7Z"></path>
                                    <circle cx="7" cy="18" r="2"></circle>
                                    <circle cx="17" cy="18" r="2"></circle>
                                </svg>
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Super Fast Delivery</p>
                                <p class="mt-1 text-xs text-slate-600">Fast delivery services, safe and secure from
                                    damage.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="flex flex-wrap items-center justify-between gap-4" data-reveal-child>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Testimonials</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900">Loved by busy households.</h2>
                <p class="mt-2 text-sm text-slate-600">Real feedback from families who shop every week.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">4.9/5 rating</span>
                <span>2,300+ reviews</span>
                <span class="rounded-full border border-green-200 bg-white px-3 py-1 font-semibold text-green-700">Trusted
                    by
                    10k+ families</span>
            </div>
        </div>
        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            <div class="group rounded-2xl border border-green-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                data-reveal-child>
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-sm font-semibold text-green-700">
                        AK
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Ayesha K.</p>
                        <p class="text-xs text-slate-500">Brooklyn, NY</p>
                    </div>
                    <div class="ml-auto flex items-center gap-1 text-amber-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-600">"Everything arrives crisp and chilled. The 90-minute window
                    is a game
                    changer."</p>
            </div>
            <div class="group rounded-2xl border border-green-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                data-reveal-child>
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-sm font-semibold text-green-700">
                        MR
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Miguel R.</p>
                        <p class="text-xs text-slate-500">Austin, TX</p>
                    </div>
                    <div class="ml-auto flex items-center gap-1 text-amber-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-600">"Love the substitution approvals. I always know what I am
                    getting."</p>
            </div>
            <div class="group rounded-2xl border border-green-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                data-reveal-child>
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-sm font-semibold text-green-700">
                        NS
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Nina S.</p>
                        <p class="text-xs text-slate-500">San Jose, CA</p>
                    </div>
                    <div class="ml-auto flex items-center gap-1 text-amber-400">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-sm text-slate-600">"The bundles and recipes keep us inspired all week long."
                </p>
            </div>
        </div>
    </section>

    <!--about section -->
    <section class="mx-auto mt-16 max-w-7xl px-4 pb-16 sm:px-6 lg:px-8" data-reveal>
        <div class="flex flex-wrap items-center justify-between gap-4" data-reveal-child>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Why choose us</p>
                <h2 class="mt-2 text-3xl font-semibold text-slate-900">Why shoppers stay with FreshCart.</h2>
                <p class="mt-2 text-sm text-slate-600">Premium service backed by real-time support and transparent
                    standards.
                </p>
            </div>
            <span
                class="rounded-full border border-green-200 bg-green-50 px-4 py-2 text-xs font-semibold text-green-700">Trusted
                by 12k+ families</span>
        </div>
        <div class="mt-8 grid gap-6 lg:grid-cols-3">
            <div class="group rounded-2xl border border-green-100 bg-white p-6 text-left shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                data-reveal-child>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M12 22s8-4 8-10V6l-8-4-8 4v6c0 6 8 10 8 10Z"></path>
                    </svg>
                </span>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Freshness you can taste</h3>
                <p class="mt-2 text-sm text-slate-600">Local farms, careful handling, zero compromise.</p>
            </div>
            <div class="group rounded-2xl border border-green-100 bg-white p-6 text-left shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                data-reveal-child>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M12 8v8"></path>
                        <path d="M8 12h8"></path>
                        <circle cx="12" cy="12" r="9"></circle>
                    </svg>
                </span>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Smart substitutions</h3>
                <p class="mt-2 text-sm text-slate-600">Approve swaps instantly, stay in control.</p>
            </div>
            <div class="group rounded-2xl border border-green-100 bg-white p-6 text-left shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                data-reveal-child>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M3 12h4l2 4 4-8 2 4h6"></path>
                    </svg>
                </span>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">Fast, tracked delivery</h3>
                <p class="mt-2 text-sm text-slate-600">Precise windows with live tracking.</p>
            </div>
        </div>
    </section>

@endsection
