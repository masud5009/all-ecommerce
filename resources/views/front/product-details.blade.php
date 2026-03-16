@extends('front.layout')

@php
    $p = $productDetail ?? [];
    $productId = $p['id'] ?? '';
    $productName = $p['name'] ?? 'Product';
    $productCategory = $p['category'] ?? 'Category';
    $productBadge = $p['badge'] ?? $productCategory;
    $productImage = $p['image'] ?? 'about:blank';
    $productImages = $p['images'] ?? [$productImage];
    $productSummary = $p['summary'] ?? '';
    $productDescription = $p['description'] ?? $productSummary;
    $productRating = $p['rating'] ?? 4.7;
    $productReviews = $p['reviews'] ?? 0;
    $productUnits = $p['units'] ?? [];
    $productNutrition = $p['nutrition'] ?? [];
    $productReviewList = $p['reviewList'] ?? [];
    $isDeal = $p['isDeal'] ?? false;
    $isPopular = $p['popular'] ?? false;
    $productStock = $p['stock'] ?? 0;

    // Ensure at least one unit
    if (empty($productUnits)) {
        $productUnits = [['label' => '1 unit', 'price' => 0, 'oldPrice' => 0]];
    }

    // Ensure images array
    if (empty($productImages)) {
        $productImages = [$productImage];
    }
@endphp

@section('title', $productName . ' | FreshCart')
@section('page', 'details')

@section('content')
    <div class="relative overflow-hidden bg-gradient-to-b from-green-50/80 via-white to-white">
        <div class="pointer-events-none absolute -left-20 top-6 h-52 w-52 rounded-full bg-green-200/40 blur-3xl"
            aria-hidden="true"></div>
        <div class="pointer-events-none absolute right-0 top-0 h-64 w-64 rounded-full bg-emerald-200/35 blur-3xl"
            aria-hidden="true"></div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('frontend.index') }}"
                    class="rounded-full border border-green-100 bg-white px-3 py-1 transition hover:border-green-300 hover:text-green-700">
                    Home
                </a>
                <span>/</span>
                <a href="{{ route('frontend.shop') }}"
                    class="rounded-full border border-green-100 bg-white px-3 py-1 transition hover:border-green-300 hover:text-green-700">
                    Shop
                </a>
                <span>/</span>
                <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                    {{ $productName }}
                </span>
            </nav>

            {{-- Product Detail Grid --}}
            <div class="grid gap-8 lg:grid-cols-[1.08fr_0.92fr]" data-product-detail data-product-id="{{ $productId }}">
                {{-- Left: Images --}}
                <div class="space-y-4">
                    {{-- Main Image --}}
                    <div data-magnify
                        class="magnify overflow-hidden rounded-3xl border border-green-100 bg-white shadow-[0_22px_55px_rgba(15,23,42,0.12)]">
                        <img src="{{ $productImage }}" alt="{{ $productName }}"
                            class="h-[360px] w-full object-cover sm:h-[430px] lg:h-[520px]" id="mainProductImage" data-magnify-image>
                    </div>

                    {{-- Thumbnails --}}
                    @if (count($productImages) > 1)
                        <div class="grid grid-cols-4 gap-3 sm:grid-cols-6">
                            @foreach ($productImages as $index => $img)
                                <button type="button"
                                    class="group overflow-hidden rounded-2xl border {{ $index === 0 ? 'border-green-500 ring-2 ring-green-200' : 'border-green-100' }} bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                    aria-label="View image {{ $index + 1 }}"
                                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}" data-thumb
                                    onclick="var img=document.getElementById('mainProductImage'); img.src='{{ $img }}'; var c=document.querySelector('[data-magnify]'); if(c){c.style.backgroundImage='url({{ $img }})'} document.querySelectorAll('[data-thumb]').forEach(function(b){b.classList.remove('border-green-500','ring-2','ring-green-200');b.classList.add('border-green-100');}); this.classList.remove('border-green-100'); this.classList.add('border-green-500','ring-2','ring-green-200');">
                                    <img src="{{ $img }}"
                                        alt="{{ $productName }} thumbnail {{ $index + 1 }}" loading="lazy"
                                        decoding="async"
                                        class="h-20 w-full object-cover transition duration-300 group-hover:scale-105">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Right: Product Info --}}
                <div>
                    <div
                        class="rounded-3xl border border-green-100 bg-white p-6 shadow-[0_16px_45px_rgba(15,23,42,0.1)] sm:p-8">
                        {{-- Category & Badges --}}
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                                {{ $productCategory }}
                            </span>
                            @if ($isDeal)
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 font-semibold text-amber-700">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" />
                                    </svg>
                                    Deal
                                </span>
                            @endif
                            @if ($isPopular)
                                <span class="rounded-full bg-blue-100 px-3 py-1 font-semibold text-blue-700">
                                    Popular
                                </span>
                            @endif
                        </div>

                        {{-- Product Name --}}
                        <h1 class="mt-4 text-2xl font-semibold leading-tight text-slate-900 sm:text-3xl">
                            {{ $productName }}
                        </h1>

                        {{-- Rating --}}
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                            <span class="flex items-center gap-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($productRating))
                                        <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor"
                                            aria-hidden="true">
                                            <path
                                                d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                                        </svg>
                                    @else
                                        <svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24" fill="currentColor"
                                            aria-hidden="true">
                                            <path
                                                d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                                        </svg>
                                    @endif
                                @endfor
                            </span>
                            <span class="text-slate-500">{{ number_format($productRating, 1) }}
                                ({{ $productReviews }} reviews)</span>
                        </div>

                        {{-- Summary --}}
                        @if (!empty($productSummary))
                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                {{ $productSummary }}
                            </p>
                        @endif

                        {{-- Units/Variants Selection --}}
                        <div class="mt-5">
                            <p class="text-sm font-semibold text-slate-900">Choose size</p>
                            <div class="mt-3 grid gap-2">
                                @foreach ($productUnits as $index => $unit)
                                    <label class="group relative flex cursor-pointer items-center gap-3">
                                        <input class="peer sr-only" type="radio" name="productUnit"
                                            value="{{ $index }}" data-detail-unit
                                            data-variant-id="{{ $unit['variant_id'] ?? '' }}"
                                            data-price="{{ $unit['price'] }}"
                                            data-old-price="{{ $unit['oldPrice'] ?? '' }}"
                                            {{ $index === 0 ? 'checked' : '' }}>
                                        <div
                                            class="flex w-full items-center justify-between rounded-2xl border border-green-100 bg-white px-5 py-4 text-sm shadow-sm transition hover:border-green-300 hover:shadow-md peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:ring-2 peer-checked:ring-green-200">
                                            <div>
                                                <p class="font-semibold text-slate-900">{{ $unit['label'] }}</p>
                                                @if (!empty($unit['sku']))
                                                    <p class="text-xs text-slate-400">SKU: {{ $unit['sku'] }}</p>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <p class="font-semibold text-slate-900">
                                                    {{ currency_symbol($unit['price']) }}</p>
                                                @if (!empty($unit['oldPrice']) && $unit['oldPrice'] > $unit['price'])
                                                    <p class="text-xs text-slate-400 line-through">
                                                        {{ currency_symbol($unit['oldPrice']) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Add to Cart & Actions --}}
                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            {{-- Quantity Selector --}}
                            <div class="inline-flex items-center rounded-2xl border border-green-200 bg-white">
                                <button type="button"
                                    class="flex h-12 w-12 items-center justify-center rounded-l-2xl text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus:outline-none"
                                    onclick="var q=document.getElementById('productQty'); var v=parseInt(q.value)||1; if(v>1){q.value=v-1;}"
                                    aria-label="Decrease quantity">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </button>
                                <input type="number" id="productQty" value="1" min="1" max="99"
                                    class="h-12 w-14 border-x border-green-200 bg-transparent text-center text-sm font-semibold text-slate-900 focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                                    readonly>
                                <button type="button"
                                    class="flex h-12 w-12 items-center justify-center rounded-r-2xl text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus:outline-none"
                                    onclick="var q=document.getElementById('productQty'); var v=parseInt(q.value)||1; if(v<99){q.value=v+1;}"
                                    aria-label="Increase quantity">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M12 5v14M5 12h14"></path>
                                    </svg>
                                </button>
                            </div>

                            <button type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                data-action="add">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M6 7h12l1 12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L6 7Z"></path>
                                    <path d="M9 7V6a3 3 0 0 1 6 0v1"></path>
                                </svg>
                                Add to cart
                            </button>
                            <a href="{{ route('frontend.shop') }}"
                                class="inline-flex items-center justify-center rounded-2xl border border-green-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-green-300 hover:text-green-700">
                                Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs Section --}}
            <section class="mt-10 rounded-3xl border border-green-100 bg-white p-6 shadow-sm sm:p-8" data-tabs>
                {{-- Tab Navigation --}}
                <div class="flex flex-wrap items-center gap-2 border-b border-green-100 pb-3">
                    <button class="rounded-full border-b-2 border-green-600 px-4 py-2 text-sm font-semibold text-green-700"
                        type="button" data-tab-target="description">
                        Description
                    </button>
                    <button
                        class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 transition hover:text-green-700"
                        type="button" data-tab-target="nutrition">
                        Nutrition
                    </button>
                    <button
                        class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 transition hover:text-green-700"
                        type="button" data-tab-target="reviews">
                        Reviews
                    </button>
                </div>

                {{-- Tab Content --}}
                <div class="mt-5 space-y-4">
                    {{-- Description Tab --}}
                    <div class="text-sm leading-7 text-slate-600" data-tab="description">
                        {!! $productDescription !!}
                    </div>

                    {{-- Nutrition Tab --}}
                    <div class="hidden text-sm leading-7 text-slate-600" data-tab="nutrition">
                        @if (count($productNutrition) > 0)
                            <ul class="list-disc space-y-2 pl-5">
                                @foreach ($productNutrition as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-slate-500">No nutrition information available.</p>
                        @endif
                    </div>

                    {{-- Reviews Tab --}}
                    <div class="hidden grid gap-3" data-tab="reviews">
                        @if (count($productReviewList) > 0)
                            @foreach ($productReviewList as $review)
                                <div class="rounded-2xl border border-green-100 bg-white p-4 shadow-sm">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-slate-900">{{ $review['name'] }}</p>
                                        <span class="flex items-center gap-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= ($review['rating'] ?? 5))
                                                    <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path
                                                            d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                                                    </svg>
                                                @else
                                                    <svg class="h-4 w-4 text-slate-300" viewBox="0 0 24 24"
                                                        fill="currentColor" aria-hidden="true">
                                                        <path
                                                            d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z" />
                                                    </svg>
                                                @endif
                                            @endfor
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-slate-600">{{ $review['text'] }}</p>
                                </div>
                            @endforeach
                        @else
                            <div
                                class="rounded-2xl border border-dashed border-green-200 bg-white p-6 text-center text-sm text-slate-500">
                                No reviews yet. Be the first to review this product!
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            {{-- You May Also Like --}}
            <section class="mt-10" data-reveal>
                <div class="flex flex-wrap items-end justify-between gap-3" data-reveal-child>
                    <div>
                        <h2 class="text-2xl font-semibold text-slate-900">You may also like</h2>
                    </div>
                    <a href="{{ route('frontend.shop') }}"
                        class="inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:border-green-600 hover:bg-green-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2">
                        Browse all
                    </a>
                </div>
                @if (!empty($youMayAlsoLikeProducts) && count($youMayAlsoLikeProducts) > 0)
                    <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach ($youMayAlsoLikeProducts as $relatedProduct)
                            @include('front.home.partials.product-card', ['product' => $relatedProduct])
                        @endforeach
                    </div>
                @else
                    <div
                        class="mt-6 rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500">
                        {{ __('No products available right now.') }}
                    </div>
                @endif
            </section>
        </div>
    </div>

    @if (!empty($productDetail))
        <script>
            window.serverProductDetail = @json($productDetail);
        </script>
    @endif

    <script>
        (function() {
            const container = document.querySelector('[data-magnify]');
            if (!container) return;

            const img = container.querySelector('[data-magnify-image]') || container.querySelector('img');
            if (!img) return;

            const setBackground = () => {
                const source = img.currentSrc || img.src;
                if (source) container.style.backgroundImage = `url("${source}")`;
            };

            const updatePosition = (e) => {
                const rect = container.getBoundingClientRect();
                const x = Math.max(0, Math.min(100, ((e.clientX - rect.left) / rect.width) * 100));
                const y = Math.max(0, Math.min(100, ((e.clientY - rect.top) / rect.height) * 100));
                container.style.backgroundPosition = `${x}% ${y}%`;
            };

            container.addEventListener('pointerenter', (e) => {
                if (e.pointerType === 'touch') return;
                setBackground();
                container.classList.add('is-magnifying');
                updatePosition(e);
            });

            container.addEventListener('pointermove', (e) => {
                if (container.classList.contains('is-magnifying')) updatePosition(e);
            });

            container.addEventListener('pointerleave', () => {
                container.classList.remove('is-magnifying');
                container.style.backgroundImage = '';
                container.style.backgroundPosition = '';
            });

            img.addEventListener('load', () => {
                if (container.classList.contains('is-magnifying')) setBackground();
            });
        })();
    </script>
@endsection
