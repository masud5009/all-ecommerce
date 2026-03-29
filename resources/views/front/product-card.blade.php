@php
    use Carbon\Carbon;

    $productContent = $product->content->first();
    $productTitle = $productContent->title ?? __('Untitled Product');

    $variations = $product->variations ?? collect();

    $totalVariantStock = $variations->sum('stock');
    $variantStock = $totalVariantStock > 0 ? __('In Stock') : __('Stock Out');

    $variantBasePrices = $variations
        ->map(function ($variation) use ($product) {
            return (float) ($variation->price ?? ($product->current_price ?? 0));
        })
        ->values();

    $min_variant_price = $variantBasePrices->isNotEmpty() ? (float) $variantBasePrices->min() : 0;
    $max_variant_price = $variantBasePrices->isNotEmpty() ? (float) $variantBasePrices->max() : 0;

    $now = Carbon::now();

    $flashDiscountPercent = (float) ($product->flash_sale_price ?? 0);

    $isFlashSaleActive =
        (int) ($product->flash_sale_status ?? 0) === 1 &&
        $flashDiscountPercent > 0 &&
        !empty($product->flash_sale_start_at) &&
        !empty($product->flash_sale_end_at) &&
        $now->between(Carbon::parse($product->flash_sale_start_at), Carbon::parse($product->flash_sale_end_at));

    if ($isFlashSaleActive) {
        $flashDiscountPercent = min($flashDiscountPercent, 100);
    }

    if ($product->has_variants == 0 || $variations->isEmpty()) {
        $currentPrice = (float) ($product->current_price ?? 0);
        $displayPrice = $isFlashSaleActive ? max($currentPrice * (1 - $flashDiscountPercent / 100), 0) : $currentPrice;

        $oldPrice = $isFlashSaleActive ? $currentPrice : $product->previous_price ?? null;
    } else {
        $displayPrice = $isFlashSaleActive
            ? max($min_variant_price * (1 - $flashDiscountPercent / 100), 0)
            : $min_variant_price;
        $oldPrice = $isFlashSaleActive ? $min_variant_price : null;

        $min_variant_price = $displayPrice;
    }

    $stockLabel =
        $product->has_variants == 0 ? (($product->stock ?? 0) > 0 ? __('In Stock') : __('Stock Out')) : $variantStock;

    // Build quick-view data for JS (modal, cart, variation change)
    $quickViewUnits = [];
    if ($product->has_variants == 0 || $variations->isEmpty()) {
        $quickViewUnits[] = [
            'label' => '1 unit',
            'price' => (float) $displayPrice,
            'oldPrice' => (float) ($oldPrice ?? 0),
        ];
    } else {
        foreach ($variations as $vIdx => $variation) {
            $variantParts = collect($variation->variantValues ?? [])
                ->sortBy(fn($vv) => optional(optional($vv->optionValue)->option)->position ?? PHP_INT_MAX)
                ->map(function ($vv) {
                    $option = optional($vv->optionValue)->option;
                    $value = optional($vv->optionValue)->value;
                    if (!$option || $value === null) {
                        return null;
                    }
                    return $option->name . ': ' . $value;
                })
                ->filter()
                ->values();

            $quickViewUnits[] = [
                'label' => $variantParts->isNotEmpty()
                    ? $variantParts->implode(', ')
                    : __('Option') . ' ' . ($vIdx + 1),
                'price' => $isFlashSaleActive
                    ? max(
                        ((float) ($variation->price ?? ($product->current_price ?? 0))) *
                            (1 - $flashDiscountPercent / 100),
                        0,
                    )
                    : (float) ($variation->price ?? ($product->current_price ?? 0)),
                'oldPrice' => $isFlashSaleActive
                    ? (float) ($variation->price ?? ($product->current_price ?? 0))
                    : (float) ($product->previous_price ?? 0),
            ];
        }
    }

    $productImage = !empty($product->thumbnail) ? asset('assets/img/product/' . $product->thumbnail) : '';
    $summaryText = trim(
        preg_replace(
            '/\s+/',
            ' ',
            strip_tags((string) ($productContent->summary ?? ($productContent->description ?? ''))),
        ),
    );

    $reviewCount = (int) ($product->reviews_count ?? 0);
    $averageRating = $reviewCount > 0 ? round((float) ($product->reviews_avg_rating ?? 0), 1) : 0;

    if (!isset($product->reviews_count) || !isset($product->reviews_avg_rating)) {
        $reviewSummary = \App\Models\ProductReview::query()
            ->where('product_id', $product->id)
            ->selectRaw('COALESCE(AVG(rating), 0) as avg_rating, COUNT(*) as total_reviews')
            ->first();

        $reviewCount = (int) ($reviewSummary->total_reviews ?? 0);
        $averageRating = $reviewCount > 0 ? round((float) ($reviewSummary->avg_rating ?? 0), 1) : 0;
    }

    $quickViewData = [
        'id' => (string) $product->id,
        'name' => $productTitle,
        'image' => $productImage,
        'images' => $productImage ? [$productImage] : [],
        'description' => $summaryText ?? '',
        'rating' => $averageRating,
        'reviews' => $reviewCount,
        'badge' => $isFlashSaleActive ? 'Flash Sale' : 'Featured',
        'units' => $quickViewUnits,
        'isDeal' => $isFlashSaleActive || (!empty($oldPrice) && (float) $oldPrice > (float) ($displayPrice ?? 0)),
    ];
@endphp

<article
    class="group relative flex h-full flex-col rounded-2xl border border-green-100 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-200 hover:shadow-[0_20px_45px_rgba(15,23,42,0.12)]"
    data-reveal-child data-featured-card data-product-id="{{ $product->id }}" data-product-name="{{ $productTitle }}"
    data-product-json="{{ json_encode($quickViewData) }}">

    @if ($isFlashSaleActive)
        <span
            class="absolute left-4 top-4 rounded-full bg-red-600 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white shadow-sm">
            {{ __('Flash Sale') }}
        </span>
    @endif

    <div class="relative overflow-hidden rounded-2xl bg-green-50">
        <a href="{{ route('frontend.shop.details', ['id' => $product->id]) }}" class="block">
            <img src="{{ asset('assets/img/product/' . $product->thumbnail) }}" alt="{{ $productTitle }}"
                class="h-40 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy"
                decoding="async">
        </a>

        <button type="button" data-action="quick-view" data-product-id="{{ $product->id }}"
            class="absolute bottom-3 right-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-600 text-white shadow-lg transition duration-300 hover:bg-green-700"
            aria-label="Quick view {{ $productTitle }}">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                aria-hidden="true">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
            </svg>
        </button>
    </div>

    <div class="mt-4 space-y-2">
        <div class="flex items-start justify-between gap-2">
            <a href="{{ route('frontend.shop.details', ['id' => $product->id]) }}"
                class="text-sm font-semibold text-slate-900 transition hover:text-green-700">
                {{ $productTitle }}
            </a>
        </div>

        <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
            <span class="flex items-center gap-1">
                <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                </svg>
                <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                </svg>
                <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                </svg>
                <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                </svg>
                <svg class="h-4 w-4 text-amber-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
                </svg>
            </span>

            <span>{{ number_format($averageRating, 1) }} ({{ $reviewCount }}
                {{ \Illuminate\Support\Str::plural('review', $reviewCount) }})</span>

            <span class="rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-semibold text-green-700">
                {{ $stockLabel }}
            </span>
        </div>

        <div class="flex items-end justify-between gap-3">
            @if ($product->has_variants == 0 || $variations->isEmpty())
                <div>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ currency_symbol($displayPrice) }}
                    </p>

                    @if (!empty($oldPrice) && $oldPrice > $displayPrice)
                        <p class="text-xs text-slate-400 line-through">
                            {{ currency_symbol($oldPrice) }}
                        </p>
                    @endif
                </div>
            @else
                <div>
                    <p class="text-lg font-semibold text-slate-900">
                        {{ currency_symbol($min_variant_price) }} - {{ currency_symbol($max_variant_price) }}
                    </p>
                    @if ($isFlashSaleActive)
                        @php
                            $oldMinVariantPrice = $variantBasePrices->isNotEmpty()
                                ? (float) $variantBasePrices->min()
                                : 0;
                            $oldMaxVariantPrice = $variantBasePrices->isNotEmpty()
                                ? (float) $variantBasePrices->max()
                                : 0;
                        @endphp
                        <p class="text-xs text-slate-400 line-through">
                            {{ currency_symbol($oldMinVariantPrice) }} - {{ currency_symbol($oldMaxVariantPrice) }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</article>
