@php
    $p = $productDetail ?? [];
    $productId = $p['id'] ?? '';
    $productName = $p['name'] ?? 'Product';
    $productCategory = $p['category'] ?? 'Category';
    $productBadge = $p['badge'] ?? $productCategory;
    $productImage = $p['image'] ?? 'about:blank';
    $productImages = $p['images'] ?? [$productImage];
    $productSummary = $p['summary'] ?? '';
    $productRating = $p['rating'] ?? 0;
    $productReviews = $p['reviews'] ?? 0;
    $productUnits = $p['units'] ?? [];
    $isDeal = $p['isDeal'] ?? false;
    $productStock = $p['stock'] ?? 0;
    $productUrl = $p['url'] ?? '#';

    if (empty($productUnits)) {
        $productUnits = [['label' => '1 unit', 'price' => 0, 'oldPrice' => 0, 'variant_id' => null]];
    }

    if (empty($productImages)) {
        $productImages = [$productImage];
    }
@endphp

<div class="grid gap-6 md:grid-cols-2" data-quickview-product data-product-id="{{ $productId }}">
    {{-- Left: Product Image --}}
    <div class="space-y-4">
        <div data-magnify class="magnify overflow-hidden rounded-2xl border border-green-100 bg-green-50">
            <img src="{{ $productImage }}" alt="{{ $productName }}"
                class="h-64 w-full object-cover sm:h-80" id="quickviewMainImage" data-magnify-image>
        </div>

        {{-- Thumbnails --}}
        @if (count($productImages) > 1)
            <div class="grid grid-cols-4 gap-2">
                @foreach ($productImages as $index => $img)
                    <button type="button"
                        class="group overflow-hidden rounded-xl border {{ $index === 0 ? 'border-green-500 ring-2 ring-green-200' : 'border-green-100' }} bg-white shadow-sm transition hover:shadow-md"
                        data-quickview-thumb
                        onclick="var img=document.getElementById('quickviewMainImage'); img.src='{{ $img }}'; var c=document.querySelector('[data-quickview-product] [data-magnify]'); if(c){c.style.backgroundImage='url({{ $img }})'} document.querySelectorAll('[data-quickview-thumb]').forEach(function(b){b.classList.remove('border-green-500','ring-2','ring-green-200');b.classList.add('border-green-100');}); this.classList.remove('border-green-100'); this.classList.add('border-green-500','ring-2','ring-green-200');">
                        <img src="{{ $img }}" alt="{{ $productName }} thumbnail {{ $index + 1 }}"
                            loading="lazy" decoding="async"
                            class="h-16 w-full object-cover transition duration-300 group-hover:scale-105">
                    </button>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Right: Product Info --}}
    <div class="flex flex-col">
        {{-- Category & Badges --}}
        <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                {{ $productCategory }}
            </span>
            @if ($isDeal)
                <span class="rounded-full bg-red-100 px-3 py-1 font-semibold text-red-700">
                    Hot Deal
                </span>
            @endif
        </div>

        {{-- Title --}}
        <h2 id="quickview-title" class="mt-3 text-xl font-semibold text-slate-900 sm:text-2xl">
            {{ $productName }}
        </h2>

        {{-- Rating --}}
        <div class="mt-2 flex items-center gap-2 text-sm">
            <div class="flex items-center gap-0.5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="h-4 w-4 {{ $i <= floor($productRating) ? 'text-amber-400' : 'text-slate-200' }}"
                        viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                    </svg>
                @endfor
            </div>
            <span class="text-slate-600">{{ $productRating }}</span>
            <span class="text-slate-400">({{ $productReviews }} reviews)</span>
        </div>

        {{-- Price Display --}}
        <div class="mt-4">
            @php $firstUnit = $productUnits[0] ?? ['price' => 0, 'oldPrice' => 0]; @endphp
            <p class="text-2xl font-semibold text-slate-900" data-quickview-price>
                {{ currency_symbol($firstUnit['price']) }}
            </p>
            @if (!empty($firstUnit['oldPrice']) && $firstUnit['oldPrice'] > $firstUnit['price'])
                <p class="text-sm text-slate-400 line-through" data-quickview-oldprice>
                    {{ currency_symbol($firstUnit['oldPrice']) }}
                </p>
            @endif
        </div>

        {{-- Summary --}}
        @if (!empty($productSummary))
            <p class="mt-4 text-sm leading-relaxed text-slate-600 line-clamp-3">
                {{ $productSummary }}
            </p>
        @endif

        {{-- Units/Variants Selection --}}
        @if (count($productUnits) > 1)
            <div class="mt-4">
                <p class="text-sm font-semibold text-slate-900">Select Option</p>
                <div class="mt-2 grid gap-2 max-h-40 overflow-y-auto">
                    @foreach ($productUnits as $index => $unit)
                        <label class="group relative flex cursor-pointer items-center gap-3">
                            <input class="peer sr-only" type="radio" name="quickviewUnit"
                                value="{{ $index }}"
                                data-variant-id="{{ $unit['variant_id'] ?? '' }}"
                                data-price="{{ $unit['price'] }}"
                                data-old-price="{{ $unit['oldPrice'] ?? '' }}"
                                {{ $index === 0 ? 'checked' : '' }}>
                            <div class="flex w-full items-center justify-between rounded-xl border border-green-100 bg-white px-4 py-3 text-sm shadow-sm transition hover:border-green-300 peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:ring-2 peer-checked:ring-green-200">
                                <p class="font-medium text-slate-900">{{ $unit['label'] }}</p>
                                <p class="font-semibold text-slate-900">{{ currency_symbol($unit['price']) }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Quantity & Add to Cart --}}
        <div class="mt-auto pt-4">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Quantity Selector --}}
                <div class="inline-flex items-center rounded-xl border border-green-200 bg-white">
                    <button type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-l-xl text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus:outline-none"
                        data-quickview-qty-dec
                        aria-label="Decrease quantity">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M5 12h14"></path>
                        </svg>
                    </button>
                    <input type="number" data-quickview-qty value="1" min="1" max="99"
                        class="h-10 w-12 border-x border-green-200 bg-transparent text-center text-sm font-semibold text-slate-900 focus:outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
                        readonly>
                    <button type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-r-xl text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus:outline-none"
                        data-quickview-qty-inc
                        aria-label="Increase quantity">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 5v14M5 12h14"></path>
                        </svg>
                    </button>
                </div>

                <button type="button"
                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                    data-quickview-add-cart>
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 7h12l1 12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L6 7Z"></path>
                        <path d="M9 7V6a3 3 0 0 1 6 0v1"></path>
                    </svg>
                    Add to Cart
                </button>
            </div>

            {{-- View Full Details Link --}}
            <a href="{{ $productUrl }}"
                class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-green-300 hover:text-green-700">
                View Full Details
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
