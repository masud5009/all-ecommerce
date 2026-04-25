@extends('front.layout')

@php
    $productName = $product_content->title ?? 'Product Name';
    $productCategory = App\Models\ProductCategory::where('id', $product->category_id)->value('name') ?? 'Category';
    $productId = $product->id;
    // Ensure at least one unit
    if (empty($variants)) {
        $variants = [['label' => '1 unit', 'price' => 0, 'oldPrice' => 0]];
    }

    $firstUnit = $variants[0] ?? ['price' => 0, 'oldPrice' => 0];

    $productDetail = [
        'id' => $productId,
        'name' => $productName,
        'units' => $variants,
    ];

    $authUser = Auth::guard('web')->user();
    $inWishlist = $authUser?->wishlist?->contains('product_id', $productId) ?? false;
    $contactNumber = trim((string) data_get($product_setting ?? null, 'contact_number', ''));
    $contactNumberStatus = (int) data_get($product_setting ?? null, 'contact_number_status', 0);
    $whatsappNumber = trim((string) data_get($product_setting ?? null, 'whatsapp_number', ''));
    $whatsappNumberStatus = (int) data_get($product_setting ?? null, 'whatsapp_number_status', 0);

    $contactTelLink = preg_replace('/[^0-9+]/', '', $contactNumber);
    $whatsappDigits = preg_replace('/\D+/', '', $whatsappNumber);

    $showCallButton = $contactNumberStatus === 1 && !empty($contactTelLink);
    $showWhatsappButton = $whatsappNumberStatus === 1 && !empty($whatsappDigits);
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
                    {{ __('Home') }}
                </a>
                <span>/</span>
                <a href="{{ route('frontend.shop') }}"
                    class="rounded-full border border-green-100 bg-white px-3 py-1 transition hover:border-green-300 hover:text-green-700">
                    {{ __('Shop') }}
                </a>
                <span>/</span>
                <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                    {{ $productName }}
                </span>
            </nav>

            {{-- Product Detail Grid --}}
            <div class="grid gap-8 lg:grid-cols-[1.08fr_0.92fr]" data-product-detail data-product-id="{{ $productId }}">
                <div class="space-y-4">
                    <!-- Thumbnail with Magnify -->
                    <div data-magnify
                        class="magnify overflow-hidden rounded-3xl border border-green-100 bg-white shadow-[0_22px_55px_rgba(15,23,42,0.12)]">
                        <img src="{{ asset('assets/img/product/' . $product->thumbnail) }}" alt="{{ $productName }}"
                            class="h-[360px] w-full object-cover sm:h-[430px] lg:h-[520px]" id="mainProductImage"
                            data-magnify-image>
                    </div>

                    <!-- Slider Images -->
                    @if ($product->sliderImage && $product->sliderImage->count() > 0)
                        <div class="grid grid-cols-4 gap-3 sm:grid-cols-6">
                            @foreach ($product->sliderImage as $index => $sliderImg)
                                <button type="button"
                                    class="group overflow-hidden rounded-2xl border {{ $index === 0 ? 'border-green-500 ring-2 ring-green-200' : 'border-green-100' }} bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                    aria-label="View image {{ $index + 1 }}"
                                    aria-pressed="{{ $index === 0 ? 'true' : 'false' }}" data-thumb
                                    onclick="var img=document.getElementById('mainProductImage'); img.src='{{ asset('assets/img/product/gallery/' . $sliderImg->image) }}'; var c=document.querySelector('[data-magnify]'); if(c){c.style.backgroundImage='url({{ asset('assets/img/product/gallery/' . $sliderImg->image) }})'} document.querySelectorAll('[data-thumb]').forEach(function(b){b.classList.remove('border-green-500','ring-2','ring-green-200');b.classList.add('border-green-100');}); this.classList.remove('border-green-100'); this.classList.add('border-green-500','ring-2','ring-green-200');">
                                    <img src="{{ asset('assets/img/product/gallery/' . $sliderImg->image) }}"
                                        alt="{{ $productName }} thumbnail {{ $index + 1 }}" loading="lazy"
                                        decoding="async"
                                        class="h-20 w-full object-cover transition duration-300 group-hover:scale-105">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <div
                        class="rounded-3xl border border-green-100 bg-white p-6 shadow-[0_16px_45px_rgba(15,23,42,0.1)] sm:p-8">
                        {{-- Category & Badges --}}
                        <div class="flex flex-wrap items-center gap-2 text-xs">
                            <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                                {{ $productCategory }}
                            </span>
                            @if ($isFlashSaleActive)
                                <span
                                    class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 font-semibold text-red-700">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M13 2L4 14h7l-1 8 10-14h-7l0-6z" />
                                    </svg>
                                    {{ __('Flash Sales') }}
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
                                    @if ($i <= floor($averageRating))
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
                            <span class="text-slate-500">{{ number_format($averageRating, 1) }}
                                ({{ $reviewCount }}
                                {{ \Illuminate\Support\Str::plural('review', $reviewCount) }})</span>
                        </div>

                        {{-- Price Display (quick-view style) --}}
                        <div class="mt-4">
                            <p class="text-3xl font-semibold leading-none text-slate-900" data-detail-price>
                                {{ currency_symbol($firstUnit['price'] ?? 0) }}
                            </p>
                            <p class="mt-2 text-lg text-slate-400 line-through {{ !empty($firstUnit['oldPrice']) && $firstUnit['oldPrice'] > ($firstUnit['price'] ?? 0) ? '' : 'hidden' }}"
                                data-detail-oldprice>
                                {{ currency_symbol($firstUnit['oldPrice'] ?? 0) }}
                            </p>
                        </div>

                        {{-- Summary --}}
                        @if ($product_content->summary)
                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                {{ $product_content->summary }}
                            </p>
                        @endif

                        {{-- Units/Variants Selection --}}
                        @if (count($variants) > 1)
                            <div class="mt-5">
                                <div class="mt-3 grid max-h-80 gap-2 overflow-y-auto pr-1">
                                    @foreach ($variants as $index => $unit)
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
                        @endif

                        {{-- Add to Cart & Actions --}}
                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            {{-- Quantity Selector --}}
                            <div class="inline-flex items-center rounded-2xl border border-green-200 bg-white">
                                <button type="button"
                                    class="flex h-12 w-12 items-center justify-center rounded-l-2xl text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus:outline-none"
                                    onclick="var q=document.getElementById('productQty'); var v=parseInt(q.value)||1; if(v>1){q.value=v-1;}"
                                    aria-label="Decrease quantity">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
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
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                        viewBox="0 0 24 24">
                                        <path d="M12 5v14M5 12h14"></path>
                                    </svg>
                                </button>
                            </div>

                            <button type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                data-action="add">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M6 7h12l1 12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L6 7Z"></path>
                                    <path d="M9 7V6a3 3 0 0 1 6 0v1"></path>
                                </svg>
                                Add to cart
                            </button>

                            <button type="button" data-action="toggle-wishlist" data-product-id="{{ $productId }}"
                                class="inline-flex items-center justify-center gap-2 rounded-2xl border border-green-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-green-300 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 {{ $inWishlist ? 'text-red-500' : 'text-slate-700' }}"
                                aria-label="Add to wishlist" aria-pressed="{{ $inWishlist ? 'true' : 'false' }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                                Wishlist
                            </button>

                            @if ($showCallButton || $showWhatsappButton)
                                <div
                                    class="grid w-full {{ $showCallButton && $showWhatsappButton ? 'grid-cols-2' : 'grid-cols-1' }} gap-3">
                                    @if ($showCallButton)
                                        <a href="tel:{{ $contactTelLink }}"
                                            class="inline-flex items-center justify-center gap-2 rounded-2xl border border-green-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-green-300 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372a1.5 1.5 0 0 0-1.22-1.474l-4.423-.884a1.5 1.5 0 0 0-1.48.54l-.97 1.293a1.5 1.5 0 0 1-1.609.53 12.035 12.035 0 0 1-7.18-7.18 1.5 1.5 0 0 1 .53-1.609l1.293-.97a1.5 1.5 0 0 0 .54-1.48l-.884-4.423A1.5 1.5 0 0 0 5.872 2.25H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                            </svg>
                                            Call
                                        </a>
                                    @endif

                                    @if ($showWhatsappButton)
                                        <a href="https://wa.me/{{ $whatsappDigits }}" target="_blank" rel="noopener"
                                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#25D366] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#1fba57] hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"
                                                aria-hidden="true">
                                                <path
                                                    d="M19.05 4.91A9.82 9.82 0 0 0 12.03 2C6.6 2 2.2 6.4 2.2 11.83c0 1.74.46 3.44 1.34 4.94L2 22l5.39-1.41a9.78 9.78 0 0 0 4.64 1.18h.01c5.43 0 9.83-4.4 9.83-9.83a9.8 9.8 0 0 0-2.82-7.03ZM12.04 20.1h-.01a8.12 8.12 0 0 1-4.13-1.13l-.3-.18-3.2.84.86-3.12-.2-.32a8.15 8.15 0 0 1-1.25-4.36c0-4.5 3.66-8.16 8.16-8.16 2.18 0 4.22.85 5.75 2.39a8.1 8.1 0 0 1 2.39 5.77c0 4.5-3.66 8.17-8.15 8.17Zm4.48-6.11c-.25-.13-1.47-.72-1.7-.8-.23-.08-.39-.12-.56.12-.17.25-.64.8-.79.97-.15.17-.29.19-.54.06-.25-.13-1.04-.38-1.98-1.21-.73-.65-1.22-1.45-1.36-1.69-.14-.24-.02-.37.11-.5.11-.11.25-.29.37-.43.12-.15.16-.25.25-.42.08-.17.04-.31-.02-.43-.06-.13-.56-1.35-.77-1.85-.2-.48-.4-.41-.56-.42h-.48c-.17 0-.43.06-.66.31-.23.25-.87.85-.87 2.07 0 1.21.89 2.39 1.01 2.55.12.17 1.74 2.66 4.22 3.73.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.29Z" />
                                            </svg>
                                            WhatsApp
                                        </a>
                                    @endif
                                </div>
                            @endif
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
                        {{ __('Description') }}
                    </button>
                    <button
                        class="rounded-full px-4 py-2 text-sm font-semibold text-slate-500 transition hover:text-green-700"
                        type="button" data-tab-target="reviews">
                        {{ __('Reviews') }}
                    </button>
                </div>

                {{-- Tab Content --}}
                <div class="mt-5 space-y-4">
                    {{-- Description Tab --}}
                    <div class="text-sm leading-7 text-slate-600" data-tab="description">
                        {!! $product_content->description !!}
                    </div>

                    {{-- Reviews Tab --}}
                    <div class="hidden space-y-5" data-tab="reviews">
                        @include('front.partials.product-reviews-tab', [
                            'productId' => $productId,
                            'productRating' => $averageRating,
                            'productReviews' => $reviewCount,
                            'productReviewList' => $reviewList,
                            'successMessage' => session('success'),
                        ])
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
                            @include('front.product-card', ['product' => $relatedProduct])
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
@endsection
@section('script')
    <script>
        window.serverProductDetail = @json($productDetail);

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

        (function() {
            const priceEl = document.querySelector('[data-detail-price]');
            const oldPriceEl = document.querySelector('[data-detail-oldprice]');
            const unitRadios = document.querySelectorAll('input[name="productUnit"]');
            const productData = window.serverProductDetail;

            if (!priceEl || !productData || !Array.isArray(productData.units) || !productData.units.length) {
                return;
            }

            const formatPriceLikeTemplate = (amount, template = '') => {
                const numeric = Number(amount) || 0;
                const formatted = numeric.toFixed(2);
                const sample = String(template || '').trim();
                const numberPattern = /-?[\d,.]+/;

                if (numberPattern.test(sample)) {
                    return sample.replace(numberPattern, formatted);
                }

                return `৳${formatted}`;
            };

            const updateDisplayedPricing = () => {
                const selectedRadio = document.querySelector('input[name="productUnit"]:checked');
                const index = selectedRadio ? parseInt(selectedRadio.value, 10) : 0;
                const selectedUnit = Number.isInteger(index) ?
                    (productData.units[index] || productData.units[0]) :
                    productData.units[0];

                if (!selectedUnit) return;

                const priceTemplate = priceEl.textContent || '';
                priceEl.textContent = formatPriceLikeTemplate(selectedUnit.price, priceTemplate);

                if (!oldPriceEl) return;

                const hasOldPrice =
                    selectedUnit.oldPrice !== null &&
                    selectedUnit.oldPrice !== undefined &&
                    Number(selectedUnit.oldPrice) > Number(selectedUnit.price);

                if (hasOldPrice) {
                    const oldPriceTemplate = oldPriceEl.textContent || priceTemplate;
                    oldPriceEl.textContent = formatPriceLikeTemplate(selectedUnit.oldPrice, oldPriceTemplate);
                    oldPriceEl.classList.remove('hidden');
                } else {
                    oldPriceEl.classList.add('hidden');
                }
            };

            unitRadios.forEach((radio) => {
                radio.addEventListener('change', updateDisplayedPricing);
            });

            updateDisplayedPricing();
        })();
    </script>
@endsection
