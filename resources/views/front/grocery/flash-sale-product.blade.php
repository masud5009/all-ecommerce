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
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($flashSaleCardProducts as $product)
                    @php
                        $content = $product->content->first();
                        $cardTitle = $content->title ?? 'Product #' . $product->id;
                        $cardSummaryRaw =
                            $content->summary ?? ($content->description ?? 'Limited time offer on selected items.');
                        $cardSummary = trim(preg_replace('/\s+/', ' ', strip_tags((string) $cardSummaryRaw)));
                        $discountPercent = min(max((float) ($product->flash_sale_price ?? 0), 0), 100);
                        $variations = $product->variations ?? collect();

                        if ((int) ($product->has_variants ?? 0) === 1 && $variations->isNotEmpty()) {
                            $variantPrices = $variations
                                ->map(function ($variation) use ($product) {
                                    return (float) ($variation->price ?? ($product->current_price ?? 0));
                                })
                                ->values();

                            $oldMinPrice = (float) ($variantPrices->min() ?? 0);
                            $oldMaxPrice = (float) ($variantPrices->max() ?? 0);
                            $saleMinPrice = max($oldMinPrice * (1 - $discountPercent / 100), 0);
                            $saleMaxPrice = max($oldMaxPrice * (1 - $discountPercent / 100), 0);

                            $salePriceLabel =
                                $saleMinPrice === $saleMaxPrice
                                    ? currency_symbol($saleMinPrice)
                                    : currency_symbol($saleMinPrice) . ' - ' . currency_symbol($saleMaxPrice);

                            $oldPriceLabel =
                                $oldMinPrice === $oldMaxPrice
                                    ? currency_symbol($oldMinPrice)
                                    : currency_symbol($oldMinPrice) . ' - ' . currency_symbol($oldMaxPrice);

                            $savePercent = round($discountPercent);
                        } else {
                            $currentPrice = (float) ($product->current_price ?? 0);
                            $salePrice = max($currentPrice * (1 - $discountPercent / 100), 0);
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
                            $countdownSeconds = max(
                                0,
                                now()->diffInSeconds(\Carbon\Carbon::parse($product->flash_sale_end_at), false),
                            );
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
                                    <span data-countdown
                                        data-countdown-seconds="{{ $countdownSeconds }}">02:15:32</span>
                                </span>
                                <span class="text-xs text-slate-500">{{ $stockLabel }}</span>
                            </div>
                        </div>

                        <h3 class="relative mt-4 text-2xl font-semibold text-slate-900">{{ $cardTitle }}</h3>
                        <p class="relative mt-2 text-sm text-slate-600">{{ truncateString($cardSummary, 100) }}</p>

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
                            <span
                                class="rounded-full border border-green-100 px-3 py-1 text-green-700">{{ $stockLabel }}</span>
                        </div>

                        @if (!empty($image))
                            <div class="relative mt-6 overflow-hidden rounded-2xl">
                                <img src="{{ $image }}" alt="{{ $cardTitle }}"
                                    class="h-48 w-full object-cover">
                            </div>
                        @endif

                    </a>
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
