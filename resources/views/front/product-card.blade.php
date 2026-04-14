@php
    use Carbon\Carbon;

    $productContent = $product->content->first();
    $productTitle = $productContent->title;
    $now = Carbon::now();

    $variations = \App\Support\ProductCardPrice::activeVariants($product, $product->variations ?? collect());
    $totalVariantStock = $variations->sum('stock');
    $variantStock = $totalVariantStock > 0 ? __('In Stock') : __('Stock Out');

    $flashDiscountPercent = $product->flash_sale_price ?? 0;

    $isFlashSaleActive =
        (int) ($product->flash_sale_status ?? 0) === 1 &&
        $flashDiscountPercent > 0 &&
        !empty($product->flash_sale_start_at) &&
        !empty($product->flash_sale_end_at) &&
        $now->between(Carbon::parse($product->flash_sale_start_at), Carbon::parse($product->flash_sale_end_at));

    if ($isFlashSaleActive) {
        $flashDiscountPercent = min($flashDiscountPercent, 100);
    }

    $cardPrice = \App\Support\ProductCardPrice::build($product, $isFlashSaleActive, (float) $flashDiscountPercent, $variations);

    $stockLabel =
        $product->has_variants == 0 ? (($product->stock ?? 0) > 0 ? __('In Stock') : __('Stock Out')) : $variantStock;

    $reviewCount = (int) ($product->reviews_count ?? 0);
    $averageRating = $reviewCount > 0 ? round((float) ($product->reviews_avg_rating ?? 0), 1) : 0;

    $authUser = Auth::guard('web')->user();
    $inWishlist = isset($forceInWishlist)
        ? (bool) $forceInWishlist
        : ($authUser?->wishlist?->contains('product_id', $product->id) ?? false);
@endphp

<article
    class="group relative flex h-full flex-col rounded-2xl border border-gray-200 bg-white p-4 shadow-[0_2px_8px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1 hover:border-slate-300 hover:shadow-[0_12px_28px_rgba(15,23,42,0.15)]"
    data-reveal-child data-featured-card data-product-id="{{ $product->id }}">

    @if ($isFlashSaleActive)
        <div class="absolute left-3 top-3 z-10">
            <div class="flex items-center gap-2 rounded-lg bg-[#FF4D4D] px-3 py-1.5 shadow-md">
                <svg class="h-3.5 w-3.5 flex-shrink-0 text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"></path>
                </svg>
                <span class="whitespace-nowrap text-[10px] font-bold uppercase tracking-wide text-white">
                    @if ($flashDiscountPercent > 0)
                        -{{ (int) $flashDiscountPercent }}%  {{ __('OFF') }}
                    @endif
                </span>
            </div>
        </div>
    @endif

    <div class="relative overflow-hidden rounded-2xl bg-green-50">
        <a href="{{ route('frontend.shop.details', ['id' => $product->id]) }}" class="block">
            <img src="{{ asset('assets/img/product/' . $product->thumbnail) }}" alt="{{ $productTitle }}"
                class="h-40 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"
                decoding="async">
        </a>

        <div class="absolute inset-0 flex items-center justify-center gap-3 opacity-0 transition duration-300 group-hover:opacity-100">
            <button type="button" data-action="toggle-wishlist" data-product-id="{{ $product->id }}"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-lg transition duration-300 hover:bg-rose-50 hover:text-rose-500 {{ $inWishlist ? 'text-rose-500 bg-rose-50' : '' }}"
                aria-label="Add to wishlist" aria-pressed="{{ $inWishlist ? 'true' : 'false' }}">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"
                    aria-hidden="true">
                    <path
                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z">
                    </path>
                </svg>
            </button>

            <button type="button" data-action="quick-view" data-product-id="{{ $product->id }}"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white text-slate-600 shadow-lg transition duration-300 hover:bg-green-50 hover:text-green-600"
                aria-label="Quick view {{ $productTitle }}">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    aria-hidden="true">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </button>
        </div>

        @if (!empty($stockLabel))
            <div class="absolute right-3 top-3 inline-flex">
                <span class="rounded-full bg-white/90 px-2.5 py-1 text-[10px] font-semibold text-slate-700 shadow-sm backdrop-blur-sm">
                    {{ $stockLabel }}
                </span>
            </div>
        @endif
    </div>

    <div class="mt-6 space-y-3">
        <div class="flex flex-col">
            <a href="{{ route('frontend.shop.details', ['id' => $product->id]) }}"
                class="text-[1.1rem] font-semibold text-slate-900 transition hover:text-green-700">
                {{ truncateString($productTitle, 50) }}
            </a>
        </div>

        <div class="flex flex-wrap items-center gap-1 text-xs text-slate-500">
            <span class="flex items-center gap-0.5">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $averageRating)
                        <svg class="h-3.5 w-3.5 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                        </svg>
                    @else
                        <svg class="h-3.5 w-3.5 text-slate-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                        </svg>
                    @endif
                @endfor
            </span>

            <span class="leading-none">{{ number_format($averageRating, 1) }} ({{ $reviewCount }}
                {{ \Illuminate\Support\Str::plural('review', $reviewCount) }})</span>
        </div>

        <div class="mt-4 flex flex-col gap-2">
            @if (($cardPrice['mode'] ?? 'single') === 'range')
                <div class="flex flex-col">
                    <p class="text-xl font-bold tracking-tight text-[#0f172a]">
                        {{ \App\Support\ProductCardPrice::formatRange($cardPrice) }}
                    </p>
                    @if (!empty($cardPrice['show_old_range']))
                        <p class="text-xs text-slate-400 line-through">
                            {{ \App\Support\ProductCardPrice::formatRange($cardPrice, true) }}
                        </p>
                    @endif
                </div>
            @else
                <div class="flex flex-col">
                    <p class="text-xl font-bold tracking-tight text-[#0f172a]">
                        {{ \App\Support\ProductCardPrice::formatSinglePrice($cardPrice) }}
                    </p>
                    @if (!empty($cardPrice['show_old_price']))
                        <p class="text-xs text-slate-400 line-through">
                            {{ currency_symbol($cardPrice['old_price']) }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</article>
