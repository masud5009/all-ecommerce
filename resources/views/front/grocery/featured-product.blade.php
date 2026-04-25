    <section class="mx-auto mt-10 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div
            class="rounded-3xl border border-green-100 bg-gradient-to-br from-green-50/70 via-white to-emerald-50/60 p-6 shadow-sm sm:p-8">
            <div class="flex flex-wrap items-end justify-between gap-4" data-reveal-child>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-700">
                        {{ @$sectionTitles->featured_product_title ?? __('Featured products section title') }}
                    </p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">
                        {{ @$sectionTitles->featured_product_sub_title ?? __('Featured products section sub title') }}
                    </h2>
                </div>
                @if (count($featuredProducts) > 0)
                    <a href="{{ route('frontend.shop') }}"
                        class="inline-flex items-center rounded-full border border-green-200 bg-white px-5 py-2.5 text-sm font-semibold text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:border-green-600 hover:bg-green-600 hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 focus-visible:ring-offset-2">
                        {{ __('View all products') }}
                    </a>
                @endif
            </div>

            @if (count($featuredProducts) > 0)
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($featuredProducts as $product)
                        @include('front.product-card', ['product' => $product])
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
