<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FreshCart | Premium Grocery')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('assets/admin/js/icon.js') }}" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('assets/front/css/style.css') }}">
    <style>
        .category-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .brush-ring {
            position: relative;
        }

        .brush-ring::before {
            content: "";
            position: absolute;
            inset: -6px;
            border-radius: 9999px;
            background: conic-gradient(from 110deg,
                    rgba(16, 185, 129, 0.35),
                    rgba(16, 185, 129, 0.12),
                    rgba(16, 185, 129, 0.45),
                    rgba(16, 185, 129, 0.2),
                    rgba(16, 185, 129, 0.35));
            -webkit-mask: radial-gradient(farthest-side, transparent calc(100% - 6px), #000 0);
            mask: radial-gradient(farthest-side, transparent calc(100% - 6px), #000 0);
            opacity: 0.9;
            transition: transform 0.3s ease, box-shadow 0.3s ease, opacity 0.3s ease;
            pointer-events: none;
        }

        .group:hover .brush-ring::before,
        .group:focus-visible .brush-ring::before {
            transform: scale(1.06);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.12), 0 14px 28px rgba(16, 185, 129, 0.18);
            opacity: 1;
        }
    </style>
</head>

<body class="bg-white text-slate-900" data-page="@yield('page', 'home')">
    @include('front.partials.navbar')
    @include('front.partials.cart-offcanvas')
    @include('front.partials.quickview-modal')

    <main class="pb-24 md:pb-0">
        @yield('content')
    </main>

    @include('front.partials.footer')

    <div
        class="fixed bottom-0 inset-x-0 z-40 border-t border-green-100 bg-white/95 backdrop-blur shadow-[0_-8px_20px_rgba(15,23,42,0.08)] md:hidden">
        <div class="mx-auto max-w-7xl px-4">
            <div class="grid grid-cols-4 gap-2 py-2 text-[11px] text-slate-500">
                <a href="{{ route('front.index') }}" data-nav="home"
                    class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 transition hover:bg-green-50 hover:text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M3 10l9-7 9 7v10a2 2 0 0 1-2 2h-4v-6H9v6H5a2 2 0 0 1-2-2V10Z"></path>
                    </svg>
                    Home
                </a>
                <a href="{{ route('frontend.shop') }}" data-nav="shop"
                    class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 transition hover:bg-green-50 hover:text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M4 4h6v6H4z"></path>
                        <path d="M14 4h6v6h-6z"></path>
                        <path d="M4 14h6v6H4z"></path>
                        <path d="M14 14h6v6h-6z"></path>
                    </svg>
                    Shop
                </a>
                <button type="button" data-action="open-cart-offcanvas" data-nav="shop"
                    class="relative flex flex-col items-center gap-1 rounded-2xl px-2 py-2 transition hover:bg-green-50 hover:text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <path d="M6 7h12l1 12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2L6 7Z"></path>
                        <path d="M9 7V6a3 3 0 0 1 6 0v1"></path>
                    </svg>
                    Shop
                    <span
                        class="absolute right-3 top-1 hidden min-w-[1.25rem] rounded-full bg-green-600 px-1 py-0.5 text-[10px] font-semibold text-white tabular-nums"
                        data-cart-count>0</span>
                </button>
                <a href="checkout.html" data-nav="account"
                    class="flex flex-col items-center gap-1 rounded-2xl px-2 py-2 transition hover:bg-green-50 hover:text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        aria-hidden="true">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M6 20c1.5-3 4-4 6-4s4.5 1 6 4"></path>
                    </svg>
                    Account
                </a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/front/js/app.js') }}"></script>
    <script src="{{ asset('assets/front/js/cart.js') }}"></script>
</body>

</html>
