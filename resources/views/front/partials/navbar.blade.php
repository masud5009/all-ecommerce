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
                    @if (!empty($menus))
                        @foreach ($menus as $menu)
                            @if (!empty($menu['children']))
                                {{-- Menu item with submenu --}}
                                <div class="group relative">
                                    <button type="button"
                                        class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                        aria-haspopup="true" aria-expanded="false">
                                        {{ $menu['title'] }}
                                        <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="none" stroke="currentColor"
                                            stroke-width="1.8" aria-hidden="true">
                                            <path d="M6 8l4 4 4-4"></path>
                                        </svg>
                                    </button>
                                    <span class="absolute left-0 right-0 top-full h-3" aria-hidden="true"></span>
                                    <div
                                        class="absolute left-1/2 top-full z-40 mt-2 w-56 -translate-x-1/2 rounded-2xl border border-green-100 bg-white p-3 shadow-xl opacity-0 invisible translate-y-2 transition duration-200 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 group-focus-within:visible group-focus-within:opacity-100 group-focus-within:translate-y-0">
                                        <ul class="space-y-1 text-sm text-slate-600">
                                            @foreach ($menu['children'] as $child)
                                                <li>
                                                    <a href="{{ url($child['url'] ?? '#') }}"
                                                        target="{{ $child['target'] ?? '_self' }}"
                                                        class="block rounded-xl px-4 py-2.5 transition hover:bg-green-50 hover:text-green-700">
                                                        {{ $child['title'] }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @else
                                {{-- Simple menu link --}}
                                <a href="{{ url($menu['url'] ?? '#') }}"
                                    target="{{ $menu['target'] ?? '_self' }}"
                                    class="nav-link rounded-full px-3 py-1 text-sm font-medium text-slate-600 transition hover:bg-green-50 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                                    {{ $menu['title'] }}
                                </a>
                            @endif
                        @endforeach
                    @endif
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
                    <label class="sr-only" for="lang-switch">{{ __('Language') }}</label>
                    <select id="lang-switch"
                        onchange="window.location.href='{{ url('/change-language') }}/'+this.value"
                        class="rounded-full border border-green-100 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-green-300 focus:ring-2 focus:ring-green-100">
                        @foreach (app('languages') as $language)
                            <option value="{{ $language->code }}"
                                 {{ (session('lang', optional(app('languages')->where('is_default',1)->first())->code) == $language->code) ? 'selected' : '' }}>
                                {{ $language->name }}
                            </option>
                         @endforeach
                    </select>
                </div>
                <div class="ml-auto flex items-center gap-2">
                    {{-- Cart button --}}
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

                    {{-- Account dropdown --}}
                    <div class="relative hidden md:block" id="account-dropdown-wrapper">
                        @auth('web')
                            {{-- Logged-in user dropdown --}}
                            <button type="button" id="account-btn"
                                class="inline-flex items-center gap-2 rounded-2xl border border-green-100 bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                aria-haspopup="true" aria-expanded="false" aria-controls="account-menu">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-white/20 text-white font-bold text-xs uppercase leading-none">
                                    {{ mb_substr(Auth::guard('web')->user()->username, 0, 1) }}
                                </span>
                                {{ Auth::guard('web')->user()->username }}
                                <svg class="h-4 w-4 opacity-70 transition-transform duration-200" id="account-chevron" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M6 8l4 4 4-4"/>
                                </svg>
                            </button>
                            <span class="absolute left-0 right-0 top-full h-3" aria-hidden="true"></span>
                            <div id="account-menu"
                                class="absolute right-0 top-full z-50 mt-2 w-56 origin-top-right rounded-2xl border border-green-100 bg-white p-2 shadow-xl ring-1 ring-black/5 opacity-0 invisible -translate-y-1 transition-all duration-200"
                                role="menu" aria-labelledby="account-btn">

                                {{-- User info header --}}
                                <div class="px-3 py-2.5 mb-1 border-b border-slate-100">
                                    <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::guard('web')->user()->username }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ Auth::guard('web')->user()->email }}</p>
                                </div>

                                <ul class="space-y-0.5 text-sm text-slate-600 py-1" role="none">
                                    <li role="none">
                                        <a href="{{ route('front.index') }}" role="menuitem"
                                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-green-50 hover:text-green-700">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd"/></svg>
                                            Dashboard
                                        </a>
                                    </li>
                                </ul>

                                {{-- Logout --}}
                                <div class="border-t border-slate-100 mt-1 pt-1">
                                    <a href="{{ route('user.logout') }}" role="menuitem"
                                        class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-red-600 transition hover:bg-red-50">
                                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2a.75.75 0 00-.75-.75h-5.5a.75.75 0 00-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M6 10a.75.75 0 01.75-.75h9.546l-1.048-.943a.75.75 0 111.004-1.114l2.5 2.25a.75.75 0 010 1.114l-2.5 2.25a.75.75 0 11-1.004-1.114l1.048-.943H6.75A.75.75 0 016 10z" clip-rule="evenodd"/></svg>
                                        Sign out
                                    </a>
                                </div>
                            </div>
                        @else
                            {{-- Guest dropdown --}}
                            <button type="button" id="account-btn"
                                class="inline-flex items-center gap-2 rounded-2xl border border-green-100 bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200"
                                aria-haspopup="true" aria-expanded="false" aria-controls="account-menu">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z"/></svg>
                                Account
                                <svg class="h-4 w-4 opacity-70 transition-transform duration-200" id="account-chevron" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M6 8l4 4 4-4"/>
                                </svg>
                            </button>
                            <span class="absolute left-0 right-0 top-full h-3" aria-hidden="true"></span>
                            <div id="account-menu"
                                class="absolute right-0 top-full z-50 mt-2 w-52 origin-top-right rounded-2xl border border-green-100 bg-white p-2 shadow-xl ring-1 ring-black/5 opacity-0 invisible -translate-y-1 transition-all duration-200"
                                role="menu" aria-labelledby="account-btn">
                                <ul class="space-y-1 text-sm" role="none">
                                    <li role="none">
                                        <a href="{{ route('user.login') }}" role="menuitem"
                                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-700 font-medium transition hover:bg-green-50 hover:text-green-700">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 4.25A2.25 2.25 0 015.25 2h5.5A2.25 2.25 0 0113 4.25v2a.75.75 0 01-1.5 0v-2a.75.75 0 00-.75-.75h-5.5a.75.75 0 00-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 00.75-.75v-2a.75.75 0 011.5 0v2A2.25 2.25 0 0110.75 18h-5.5A2.25 2.25 0 013 15.75V4.25z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M19 10a.75.75 0 00-.75-.75H8.704l1.048-.943a.75.75 0 10-1.004-1.114l-2.5 2.25a.75.75 0 000 1.114l2.5 2.25a.75.75 0 101.004-1.114l-1.048-.943h9.546A.75.75 0 0019 10z" clip-rule="evenodd"/></svg>
                                            Sign in
                                        </a>
                                    </li>
                                    <li role="none">
                                        <a href="{{ route('user.signup') }}" role="menuitem"
                                            class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-700 font-medium transition hover:bg-green-50 hover:text-green-700">
                                            <svg class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path d="M11 5a3 3 0 11-6 0 3 3 0 016 0zM2.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 018 18a9.953 9.953 0 01-5.385-1.572zM16.25 5.75a.75.75 0 00-1.5 0v2h-2a.75.75 0 000 1.5h2v2a.75.75 0 001.5 0v-2h2a.75.75 0 000-1.5h-2v-2z"/></svg>
                                            Create account
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endauth
                    </div>
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
                        <label class="sr-only" for="lang-switch-mobile">{{ __('Language') }}</label>
                        <select id="lang-switch-mobile"
                            onchange="window.location.href='{{ url('/change-language') }}/'+this.value"
                            class="w-full rounded-full border border-green-100 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm focus:border-green-300 focus:ring-2 focus:ring-green-100">
                         @foreach (app('languages') as $language)
                            <option value="{{ $language->code }}"
                                 {{ (session('lang', optional(app('languages')->where('is_default',1)->first())->code) == $language->code) ? 'selected' : '' }}>
                                {{ $language->name }}
                            </option>
                         @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <script>
    (function () {
        const btn = document.getElementById('account-btn');
        const menu = document.getElementById('account-menu');
        const chevron = document.getElementById('account-chevron');
        if (!btn || !menu) return;

        function openMenu() {
            menu.classList.remove('opacity-0', 'invisible', '-translate-y-1');
            menu.classList.add('opacity-100', 'visible', 'translate-y-0');
            btn.setAttribute('aria-expanded', 'true');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }

        function closeMenu() {
            menu.classList.add('opacity-0', 'invisible', '-translate-y-1');
            menu.classList.remove('opacity-100', 'visible', 'translate-y-0');
            btn.setAttribute('aria-expanded', 'false');
            if (chevron) chevron.style.transform = '';
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = btn.getAttribute('aria-expanded') === 'true';
            isOpen ? closeMenu() : openMenu();
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('account-dropdown-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                closeMenu();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeMenu();
        });
    })();
    </script>
