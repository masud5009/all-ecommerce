@extends('front.layout')

@section('title', 'Product Details | FreshCart')
@section('page', 'details')

@section('content')
    <div class="relative overflow-hidden bg-gradient-to-b from-green-50/80 via-white to-white">
        <div class="pointer-events-none absolute -left-20 top-6 h-52 w-52 rounded-full bg-green-200/40 blur-3xl"
            aria-hidden="true"></div>
        <div class="pointer-events-none absolute right-0 top-0 h-64 w-64 rounded-full bg-emerald-200/35 blur-3xl"
            aria-hidden="true"></div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            <nav class="mb-6 flex flex-wrap items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('front.index') }}"
                    class="rounded-full border border-green-100 bg-white px-3 py-1 transition hover:border-green-300 hover:text-green-700">
                    Home
                </a>
                <span>/</span>
                <a href="products.html"
                    class="rounded-full border border-green-100 bg-white px-3 py-1 transition hover:border-green-300 hover:text-green-700">
                    Shop
                </a>
                <span>/</span>
                <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700" data-detail-breadcrumb>
                    Product
                </span>
            </nav>

            <div class="grid gap-8 lg:grid-cols-[1.08fr_0.92fr]" data-product-detail>
                <div class="space-y-4">
                    <div
                        class="magnify overflow-hidden rounded-3xl border border-green-100 bg-white shadow-[0_22px_55px_rgba(15,23,42,0.12)]"
                        data-magnify>
                        <img src="{{ $productDetailData['image'] ?? 'about:blank' }}"
                            alt="{{ $productDetailData['name'] ?? 'Product preview' }}"
                            class="h-[360px] w-full object-cover sm:h-[430px] lg:h-[520px]" data-detail-main
                            data-magnify-image>
                    </div>

                    <div class="grid grid-cols-3 gap-3 sm:grid-cols-6" data-detail-thumbs></div>

                    <div class="rounded-3xl border border-green-100 bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold text-slate-900">Why shoppers trust FreshCart</p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl border border-green-100 bg-green-50/60 p-3 text-xs text-slate-600">
                                <p class="font-semibold text-slate-900">90-minute delivery</p>
                                <p class="mt-1">Live ETA and careful packaging for fresh arrival.</p>
                            </div>
                            <div class="rounded-2xl border border-green-100 bg-green-50/60 p-3 text-xs text-slate-600">
                                <p class="font-semibold text-slate-900">Farm-to-door</p>
                                <p class="mt-1">Hand-picked quality checked produce every morning.</p>
                            </div>
                            <div class="rounded-2xl border border-green-100 bg-green-50/60 p-3 text-xs text-slate-600">
                                <p class="font-semibold text-slate-900">Easy replacement</p>
                                <p class="mt-1">Instant replacement for freshness issues.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="rounded-3xl border border-green-100 bg-white p-6 shadow-[0_16px_45px_rgba(15,23,42,0.1)] sm:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                            <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700"
                                data-detail-category>Category</span>
                            <span class="hidden rounded-full border border-green-100 bg-white px-3 py-1 font-semibold text-slate-600"
                                data-detail-badge>Badge</span>
                        </div>

                        <h1 class="mt-4 text-3xl font-semibold leading-tight text-slate-900 sm:text-4xl"
                            data-detail-name>
                            Product Name
                        </h1>

                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-slate-500" data-detail-rating></div>

                        <p class="mt-4 text-sm leading-6 text-slate-600" data-detail-description>
                            Product description will appear here.
                        </p>

                        <div
                            class="mt-5 flex items-end justify-between gap-4 rounded-2xl border border-green-100 bg-green-50/60 px-4 py-3">
                            <div>
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-green-700">Price</p>
                                <p class="mt-1 text-3xl font-semibold text-slate-900" data-detail-price>$0.00</p>
                                <p class="text-sm text-slate-400 line-through hidden" data-detail-old-price>$0.00</p>
                            </div>
                            <div class="text-right text-xs text-slate-600">
                                <p class="font-semibold text-slate-900">Next slot</p>
                                <p>Today, 60-90 min</p>
                            </div>
                        </div>

                        <label class="sr-only" for="detail-unit">Choose size</label>
                        <select id="detail-unit" class="sr-only" data-detail-unit-select></select>

                        <div class="mt-5">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-900">Choose size</p>
                                <span class="text-xs text-slate-500">Flexible quantity</span>
                            </div>
                            <div class="grid gap-2" data-detail-units></div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center gap-3">
                            <button
                                class="inline-flex items-center justify-center rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                type="button" data-action="add">
                                Add to cart
                            </button>
                            <div class="hidden items-center gap-2 rounded-2xl border border-green-100 bg-white px-3 py-2"
                                data-qty-stepper>
                                <button
                                    class="h-8 w-8 rounded-full bg-green-50 text-green-700 transition hover:bg-green-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                    type="button" aria-label="Decrease quantity" data-action="dec">-</button>
                                <span class="min-w-[1.5rem] text-center text-sm font-semibold text-slate-700"
                                    data-qty>1</span>
                                <button
                                    class="h-8 w-8 rounded-full bg-green-600 text-white transition hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                    type="button" aria-label="Increase quantity" data-action="inc">+</button>
                            </div>
                            <a href="cart.html"
                                class="inline-flex items-center justify-center rounded-2xl border border-green-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:-translate-y-0.5 hover:border-green-300 hover:text-green-700">
                                View cart
                            </a>
                        </div>

                        <div class="mt-6 grid gap-2 text-xs text-slate-600 sm:grid-cols-2">
                            <div class="rounded-2xl border border-green-100 bg-white p-3">
                                <p class="font-semibold text-slate-900">Free delivery</p>
                                <p class="mt-1">On orders above $50</p>
                            </div>
                            <div class="rounded-2xl border border-green-100 bg-white p-3">
                                <p class="font-semibold text-slate-900">Secure payment</p>
                                <p class="mt-1">Card, wallet and cash support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <section class="mt-10 rounded-3xl border border-green-100 bg-white p-6 shadow-sm sm:p-8" data-tabs>
                <div class="flex flex-wrap items-center gap-2 border-b border-green-100 pb-3">
                    <button
                        class="rounded-full border-b-2 border-green-600 px-4 py-2 text-sm font-semibold text-green-700"
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

                <div class="mt-5 space-y-4">
                    <div class="text-sm leading-7 text-slate-600" data-tab="description"></div>
                    <div class="hidden text-sm leading-7 text-slate-600" data-tab="nutrition"></div>
                    <div class="hidden grid gap-3" data-tab="reviews"></div>
                </div>
            </section>

            <section class="mt-10" data-reveal>
                <div class="flex flex-wrap items-end justify-between gap-3" data-reveal-child>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Continue shopping</p>
                        <h2 class="mt-1 text-2xl font-semibold text-slate-900">You may also like</h2>
                    </div>
                    <a href="products.html" class="text-sm font-semibold text-green-700 transition hover:text-green-800">
                        Browse all
                    </a>
                </div>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4" data-products-grid data-products-source="popular"
                    data-products-limit="4"></div>
            </section>
        </div>
    </div>

    @if (!empty($productDetailData))
        <script>
            window.serverProductDetail = @json($productDetailData);
        </script>
    @endif
@endsection
