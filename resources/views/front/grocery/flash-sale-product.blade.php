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
        @if (count($flashSaleCardProducts) > 0)
            <div class="flash-sale-head-actions flex items-center gap-3" data-reveal-child>
                <a href="{{ route('frontend.shop') }}"
                    class="flash-sale-all-deals inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:border-green-600 hover:bg-green-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2">
                    {{ __('Show more products') }}
                </a>
            </div>
        @endif
    </div>

    <div class="flash-sale-shell mt-8 rounded-3xl border border-green-100 bg-gradient-to-br from-green-50/80 via-white to-emerald-50/60 p-6 shadow-sm sm:p-8"
        data-reveal-child>
        @if (count($flashSaleCardProducts) > 0)
            @php
                $authUser = Auth::guard('web')->user();
            @endphp

            <div class="flash-sale-grid grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($flashSaleCardProducts as $product)
                    @php
                        $content = $product->content->first();
                        $cardTitle = trim($content->title ?? 'Product #' . $product->id);
                        $cardSummaryRaw =
                            $content->summary ?? ($content->description ?? 'Limited time offer on selected items.');
                        $cardSummary = trim(preg_replace('/\s+/', ' ', strip_tags((string) $cardSummaryRaw)));

                        $discountPercent = min(max((float) ($product->flash_sale_price ?? 0), 0), 100);
                        $variations = $product->variations ?? collect();
                        $hasVariants = (int) ($product->has_variants ?? 0) === 1 && $variations->isNotEmpty();

                        $variantOptions = $hasVariants
                            ? $variations
                                ->values()
                                ->map(function ($variation, $index) use ($product, $discountPercent) {
                                    $variantParts = collect($variation->variantValues ?? [])
                                        ->sortBy(function ($variantValue) {
                                            return optional(optional($variantValue->optionValue)->option)->position ??
                                                PHP_INT_MAX;
                                        })
                                        ->map(function ($variantValue) {
                                            $option = optional($variantValue->optionValue)->option;
                                            $value = optional($variantValue->optionValue)->value;

                                            if (!$option || $value === null || $value === '') {
                                                return null;
                                            }

                                            return $option->name . ': ' . $value;
                                        })
                                        ->filter()
                                        ->values();

                                    $basePrice = (float) ($variation->price ?? ($product->current_price ?? 0));

                                    return [
                                        'id' => (int) $variation->id,
                                        'label' => $variantParts->isNotEmpty()
                                            ? $variantParts->implode(', ')
                                            : __('Option') . ' ' . ($index + 1),
                                        'base_price' => $basePrice,
                                        'sale_price' => max($basePrice * (1 - $discountPercent / 100), 0),
                                        'stock' => (int) ($variation->stock ?? 0),
                                    ];
                                })
                            : collect();

                        if ($hasVariants) {
                            $purchaseUnit = $variantOptions
                                ->filter(function ($variation) {
                                    return (int) ($variation['stock'] ?? 0) > 0;
                                })
                                ->sortBy('sale_price')
                                ->first()
                                ?? $variantOptions->sortBy('sale_price')->first();

                            $saleMinPrice = (float) ($variantOptions->min('sale_price') ?? 0);
                            $saleMaxPrice = (float) ($variantOptions->max('sale_price') ?? 0);
                            $oldMinPrice = (float) ($variantOptions->min('base_price') ?? 0);
                            $oldMaxPrice = (float) ($variantOptions->max('base_price') ?? 0);
                            $availableStock = (int) $variantOptions->sum('stock');
                            $defaultCartPrice = (float) ($purchaseUnit['sale_price'] ?? $saleMinPrice);
                            $defaultVariantId = $purchaseUnit['id'] ?? null;
                            $defaultVariantLabel = $purchaseUnit['label'] ?? null;
                            $savePercent = (int) round($discountPercent);

                            $salePriceLabel =
                                $saleMinPrice === $saleMaxPrice
                                    ? currency_symbol($saleMinPrice)
                                    : currency_symbol($saleMinPrice) . ' - ' . currency_symbol($saleMaxPrice);

                            $oldPriceLabel =
                                $oldMinPrice === $oldMaxPrice
                                    ? currency_symbol($oldMinPrice)
                                    : currency_symbol($oldMinPrice) . ' - ' . currency_symbol($oldMaxPrice);
                        } else {
                            $currentPrice = (float) ($product->current_price ?? 0);
                            $salePrice = max($currentPrice * (1 - $discountPercent / 100), 0);
                            $oldPrice = $currentPrice;
                            $availableStock = (int) ($product->stock ?? 0);
                            $defaultCartPrice = $salePrice;
                            $defaultVariantId = null;
                            $defaultVariantLabel = null;
                            $saveAmount = max($oldPrice - $salePrice, 0);
                            $savePercent = $oldPrice > 0 ? (int) round(($saveAmount / $oldPrice) * 100) : 0;
                            $salePriceLabel = currency_symbol($salePrice);
                            $oldPriceLabel = currency_symbol($oldPrice);
                        }

                        $isOutOfStock = $availableStock <= 0;
                        $stockLabel = $isOutOfStock ? __('Stock Out') : __('In Stock');
                        $productUrl = route('frontend.shop.details', ['id' => $product->id]);
                        $image = !empty($product->thumbnail) ? asset('assets/img/product/' . $product->thumbnail) : null;
                        $reviewCount = (int) ($product->reviews_count ?? 0);
                        $averageRating = $reviewCount > 0 ? round((float) ($product->reviews_avg_rating ?? 0), 1) : 0;
                        $ratingStars = (int) floor($averageRating);
                        $countdownSeconds = 0;

                        if (!empty($product->flash_sale_end_at)) {
                            $countdownSeconds = max(
                                0,
                                now()->diffInSeconds(\Carbon\Carbon::parse($product->flash_sale_end_at), false),
                            );
                        }

                        $inWishlist = $authUser?->wishlist?->contains('product_id', $product->id) ?? false;
                    @endphp

                    <article
                        class="flash-sale-card group relative flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-green-100 bg-white/95 {{ $isOutOfStock ? 'is-sold-out' : '' }}"
                        data-reveal-child data-flash-sale-card data-product-id="{{ $product->id }}">
                        <div class="flash-sale-card__media-wrap">
                            <a href="{{ $productUrl }}" class="flash-sale-card__media-link"
                                aria-label="{{ $cardTitle }}">
                                <div class="flash-sale-card__media">
                                    @if (!empty($image))
                                        <img src="{{ $image }}" alt="{{ $cardTitle }}"
                                            class="flash-sale-card__image" loading="lazy" decoding="async">
                                    @else
                                        <div class="flash-sale-card__placeholder">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8" aria-hidden="true">
                                                <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                                                <circle cx="8.5" cy="9" r="1.5"></circle>
                                                <path d="M21 15l-4.5-4.5L7 20"></path>
                                            </svg>
                                            <span>{{ __('No image available') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            <div class="flash-sale-card__badges">
                                <span class="flash-sale-badge flash-sale-badge--deal">
                                    <i class="fas fa-bolt text-[10px]" aria-hidden="true"></i>
                                    {{ __('Flash Sale') }}
                                </span>
                                @if ($savePercent > 0)
                                    <span class="flash-sale-badge flash-sale-badge--save">
                                        -{{ $savePercent }}%
                                    </span>
                                @endif
                            </div>

                            <div class="flash-sale-card__floating-actions">
                                <button type="button"
                                    class="flash-sale-icon-button flash-sale-card__wishlist {{ $inWishlist ? 'text-red-500' : 'text-slate-500' }}"
                                    data-action="toggle-wishlist" data-product-id="{{ $product->id }}"
                                    aria-label="Add to wishlist" aria-pressed="{{ $inWishlist ? 'true' : 'false' }}">
                                    <svg class="wishlist-icon" viewBox="0 0 24 24" fill="currentColor"
                                        aria-hidden="true">
                                        <path
                                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z">
                                        </path>
                                    </svg>
                                </button>
                            </div>

                            @if ($isOutOfStock)
                                <div class="flash-sale-card__overlay" aria-hidden="true">
                                    <span class="flash-sale-card__overlay-label">{{ __('Stock Out') }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="flash-sale-card__body">
                            <div class="flash-sale-card__meta">
                                <div class="flash-sale-card__rating {{ $reviewCount === 0 ? 'is-empty' : '' }}">
                                    <span class="flash-sale-card__stars" aria-hidden="true">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg viewBox="0 0 24 24" fill="currentColor"
                                                class="{{ $i <= $ratingStars ? 'is-filled' : 'is-empty' }}">
                                                <path
                                                    d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z">
                                                </path>
                                            </svg>
                                        @endfor
                                    </span>
                                    <span class="flash-sale-card__rating-text">
                                        @if ($reviewCount > 0)
                                            {{ number_format($averageRating, 1) }}
                                            ({{ $reviewCount }})
                                        @else
                                            {{ __('No reviews yet') }}
                                        @endif
                                    </span>
                                </div>

                                <span class="flash-sale-badge flash-sale-badge--timer">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                        aria-hidden="true">
                                        <circle cx="12" cy="12" r="9"></circle>
                                        <path d="M12 7v6l3 2"></path>
                                    </svg>
                                    <span data-countdown data-countdown-seconds="{{ $countdownSeconds }}"
                                        class="tabular-nums whitespace-nowrap"></span>
                                </span>
                            </div>

                            <div class="flash-sale-card__content">
                                <a href="{{ $productUrl }}" class="flash-sale-card__title">
                                    {{ $cardTitle }}
                                </a>
                                <p class="flash-sale-card__description">
                                    {{ $cardSummary }}
                                </p>
                            </div>

                            <div class="flash-sale-card__pricing">
                                <span class="flash-sale-card__price-current">{{ $salePriceLabel }}</span>
                                @if (!empty($oldPriceLabel) && $oldPriceLabel !== $salePriceLabel)
                                    <span class="flash-sale-card__price-old">{{ $oldPriceLabel }}</span>
                                @endif
                            </div>

                            <div class="flash-sale-card__footer">
                                <div class="flash-sale-card__footer-row">
                                    <span
                                        class="flash-sale-badge {{ $isOutOfStock ? 'flash-sale-badge--soldout' : 'flash-sale-badge--stock' }}">
                                        {{ $stockLabel }}
                                    </span>

                                    <button type="button" class="flash-sale-card__details-btn"
                                        data-action="quick-view" data-product-id="{{ $product->id }}"
                                        aria-label="Quick view {{ $cardTitle }}">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            aria-hidden="true">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <span>Quick view</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="mt-8 rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500"
                data-reveal-child>
                {{ __('NO PRODUCT FOUND!') }}
            </div>
        @endif
    </div>
</section>
