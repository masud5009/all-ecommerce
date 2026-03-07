@extends('front.layout')
@section('content')
    <!--Features section -->
    <section class="mx-auto mt-12 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="rounded-3xl border border-green-100/70 bg-white/90 p-6 shadow-sm sm:p-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="group rounded-2xl border border-green-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-300 hover:bg-green-50/40 hover:shadow-[0_20px_50px_rgba(16,185,129,0.18)]"
                    data-reveal-child>
                    <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide">
                        <span class="rounded-full bg-green-600 px-3 py-1 text-white">FreshCart</span>
                        <span class="rounded-full border border-slate-200 px-3 py-1 text-slate-400">Typical</span>
                    </div>
                    <div class="mt-5 flex items-start gap-3">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100 text-green-700 shadow-sm">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                aria-hidden="true">
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 7v6l3 2"></path>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Delivery confidence</h3>
                            <p class="mt-1 text-sm text-slate-600">90-minute slots with live tracking vs 4-hour
                                ranges.</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                        <span class="rounded-full bg-green-100 px-3 py-1 text-green-700">Live ETA</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-500">No updates</span>
                    </div>
                </div>
                <div class="group rounded-2xl border border-green-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-300 hover:bg-green-50/40 hover:shadow-[0_20px_50px_rgba(16,185,129,0.18)]"
                    data-reveal-child>
                    <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide">
                        <span class="rounded-full bg-green-600 px-3 py-1 text-white">FreshCart</span>
                        <span class="rounded-full border border-slate-200 px-3 py-1 text-slate-400">Typical</span>
                    </div>
                    <div class="mt-5 flex items-start gap-3">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100 text-green-700 shadow-sm">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                aria-hidden="true">
                                <path d="M4 14c6-1 10-5 12-10 3 5 4 10 3 14-2 6-10 8-13 3-1-2-1-5-1-7Z"></path>
                                <path d="M7 15c3-2 6-5 9-10"></path>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Freshness standards</h3>
                            <p class="mt-1 text-sm text-slate-600">Hand-picked within 12 hours vs unknown shelf
                                age.</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                        <span class="rounded-full bg-green-100 px-3 py-1 text-green-700">Farm sourced</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-500">Bulk stock</span>
                    </div>
                </div>
                <div class="group rounded-2xl border border-green-100 bg-white p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-300 hover:bg-green-50/40 hover:shadow-[0_20px_50px_rgba(16,185,129,0.18)]"
                    data-reveal-child>
                    <div class="flex items-center justify-between text-[10px] font-semibold uppercase tracking-wide">
                        <span class="rounded-full bg-green-600 px-3 py-1 text-white">FreshCart</span>
                        <span class="rounded-full border border-slate-200 px-3 py-1 text-slate-400">Typical</span>
                    </div>
                    <div class="mt-5 flex items-start gap-3">
                        <span
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100 text-green-700 shadow-sm">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                aria-hidden="true">
                                <path d="M12 3v18"></path>
                                <path d="M8 7h7a3 3 0 0 1 0 6H9a3 3 0 0 0 0 6h7"></path>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Price clarity</h3>
                            <p class="mt-1 text-sm text-slate-600">Transparent unit pricing vs surprise markups.
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                        <span class="rounded-full bg-green-100 px-3 py-1 text-green-700">No hidden fees</span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-500">Extra charges</span>
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
