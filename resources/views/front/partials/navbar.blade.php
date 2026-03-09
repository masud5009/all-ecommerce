    <nav class="site-header sticky top-0 z-50 border-b border-green-100 bg-white/90 backdrop-blur" data-header>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-4 py-4">
                <a href="{{ route('front.index') }}" class="flex items-center gap-2 text-xl font-semibold text-green-700">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-green-100">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path d="M4 14c6-1 10-5 12-10 3 5 4 10 3 14-2 6-10 8-13 3-1-2-1-5-1-7Z" />
                            <path d="M7 15c3-2 6-5 9-10" />
                        </svg>
                    </span>
                    FreshCart
                </a>
                <div class="hidden lg:flex items-center gap-6">
                    <a href="{{ route('front.index') }}" data-nav="home"
                        class="nav-link rounded-full px-3 py-1 text-sm font-medium text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                        Home
                    </a>
                    <a href="#categories"
                        class="nav-link rounded-full px-3 py-1 text-sm font-medium text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                        Categories
                    </a>
                    <a href="#deals"
                        class="nav-link rounded-full px-3 py-1 text-sm font-medium text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                        Deals
                    </a>
                    <a href="#rewards"
                        class="nav-link rounded-full px-3 py-1 text-sm font-medium text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                        Rewards
                    </a>
                    <div class="group relative">
                        <button type="button"
                            class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                            aria-haspopup="true" aria-expanded="false">
                            More
                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="none" stroke="currentColor"
                                stroke-width="1.8" aria-hidden="true">
                                <path d="M6 8l4 4 4-4"></path>
                            </svg>
                        </button>
                        <span class="absolute left-0 right-0 top-full h-3" aria-hidden="true"></span>
                        <div
                            class="absolute left-1/2 top-full z-40 mt-2 w-[640px] -translate-x-1/2 rounded-3xl border border-green-100 bg-white p-6 shadow-xl opacity-0 invisible translate-y-2 transition duration-200 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 group-focus-within:visible group-focus-within:opacity-100 group-focus-within:translate-y-0">
                            <div class="grid gap-6 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Legal</p>
                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                                        <li>
                                            <a href="products.html"
                                                class="flex items-center justify-between rounded-2xl border border-green-100 px-4 py-3 transition hover:border-green-300 hover:text-green-700">
                                                Privacy Policy
                                                <span class="text-xs text-slate-400">Updated</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="products.html"
                                                class="flex items-center justify-between rounded-2xl border border-green-100 px-4 py-3 transition hover:border-green-300 hover:text-green-700">
                                                Terms & Conditions
                                                <span class="text-xs text-slate-400">Updated</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="rounded-2xl bg-green-50 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Trust and transparency</p>
                                    <p class="mt-2 text-xs text-slate-600">Clear policies so you always know how we
                                        handle data, orders,
                                        and refunds.</p>
                                    <ul class="mt-4 space-y-2 text-xs text-slate-600">
                                        <li class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Secure checkout and data handling.
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Transparent delivery standards.
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                                            Easy returns on quality issues.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative hidden flex-1 items-center md:flex">
                    <label class="sr-only" for="search">Search groceries</label>
                    <input id="search" type="search" placeholder="Search for fresh produce, dairy, snacks"
                        class="w-full rounded-full border border-green-100 bg-white/90 py-3 pl-12 pr-4 text-sm shadow-sm outline-none transition focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-200"
                        aria-label="Search groceries">
                    <span class="pointer-events-none absolute left-4 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="M20 20l-3.5-3.5"></path>
                        </svg>
                    </span>
                </div>
                <div class="hidden md:block">
                    <label class="sr-only" for="location">Delivery location</label>
                    <select id="location"
                        class="rounded-full border border-green-100 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-green-300 focus:ring-2 focus:ring-green-100">
                        <option>Downtown</option>
                        <option>Riverside</option>
                        <option>Uptown</option>
                    </select>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    <button type="button" data-action="open-cart-offcanvas"
                        class="relative inline-flex items-center gap-2 rounded-2xl border border-green-100 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                        aria-label="View cart">
                        <svg class="h-5 w-5 text-green-700" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" aria-hidden="true">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.7 12.4a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.5l1.6-7.5H6"></path>
                        </svg>
                        Cart
                        <span
                            class="absolute -right-2 -top-2 hidden min-w-[1.5rem] rounded-full bg-green-600 px-1.5 py-0.5 text-center text-xs font-semibold text-white tabular-nums"
                            data-cart-count>0</span>
                    </button>
                    <button
                        class="hidden rounded-2xl border border-green-100 bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 md:inline-flex"
                        aria-label="Account">
                        Account
                    </button>
                </div>
                <div class="w-full md:hidden">
                    <div class="relative">
                        <label class="sr-only" for="search-mobile">Search groceries</label>
                        <input id="search-mobile" type="search" placeholder="Search for fresh produce, dairy, snacks"
                            class="w-full rounded-full border border-green-100 bg-white/90 py-3 pl-12 pr-4 text-sm shadow-sm outline-none transition focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-200"
                            aria-label="Search groceries">
                        <span class="pointer-events-none absolute left-4 top-3 text-slate-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" aria-hidden="true">
                                <circle cx="11" cy="11" r="7"></circle>
                                <path d="M20 20l-3.5-3.5"></path>
                            </svg>
                        </span>
                    </div>
                    <div class="mt-3">
                        <label class="sr-only" for="location-mobile">Delivery location</label>
                        <select id="location-mobile"
                            class="w-full rounded-full border border-green-100 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-green-300 focus:ring-2 focus:ring-green-100">
                            <option>Downtown</option>
                            <option>Riverside</option>
                            <option>Uptown</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </nav>
