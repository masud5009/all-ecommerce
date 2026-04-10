<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FreshCart | Premium Grocery')</title>

    <script>
        window.routes = {
            wishlistToggle: '{{ route('frontend.wishlist.toggle') }}',
            login: '{{ route('user.login') }}'
        };
        window.websitePlugins = {
            facebookPixelEnabled: {{ !empty($websiteInfo->facebook_pixel_status) && !empty($websiteInfo->facebook_pixel_id) ? 'true' : 'false' }},
            facebookPixelId: @json($websiteInfo->facebook_pixel_id ?? null),
            googleAnalyticsEnabled: {{ !empty($websiteInfo->google_analytics_status) && !empty($websiteInfo->google_analytics_measurement_id) ? 'true' : 'false' }},
            googleAnalyticsMeasurementId: @json($websiteInfo->google_analytics_measurement_id ?? null),
            googleRecaptchaEnabled: {{ !empty($websiteInfo->google_recaptcha_status) && !empty($websiteInfo->google_recaptcha_site_key) ? 'true' : 'false' }},
            googleRecaptchaSiteKey: @json($websiteInfo->google_recaptcha_site_key ?? null),
            currencyCode: @json($websiteInfo->currency_text ?? 'BDT')
        };
    </script>

    @if (!empty($websiteInfo->google_analytics_status) && !empty($websiteInfo->google_analytics_measurement_id))
        <script async
            src="https://www.googletagmanager.com/gtag/js?id={{ urlencode($websiteInfo->google_analytics_measurement_id) }}">
        </script>
        <script>
            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function() {
                dataLayer.push(arguments);
            };
            gtag('js', new Date());
            gtag('config', @json($websiteInfo->google_analytics_measurement_id), {
                send_page_view: true
            });
        </script>
    @endif

    @if (!empty($websiteInfo->facebook_pixel_status) && !empty($websiteInfo->facebook_pixel_id))
        <script>
            !function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = true;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = true;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s);
            }(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', @json($websiteInfo->facebook_pixel_id));
            fbq('track', 'PageView');
        </script>
    @endif

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
    @if (!empty($websiteInfo->facebook_pixel_status) && !empty($websiteInfo->facebook_pixel_id))
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ urlencode($websiteInfo->facebook_pixel_id) }}&ev=PageView&noscript=1" />
        </noscript>
    @endif

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
                <a href="{{ route('frontend.index') }}" data-nav="home"
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('assets/front/js/cart.js') }}"></script>
    <script src="{{ asset('assets/front/js/app.js') }}"></script>
    <script src="{{ asset('assets/front/js/checkout.js') }}"></script>
    <script src="{{ asset('assets/front/js/wishlist.js') }}"></script>
    <script src="{{ asset('assets/front/js/plugin-integrations.js') }}"></script>

    @if (!empty($websiteInfo->google_recaptcha_status) && !empty($websiteInfo->google_recaptcha_site_key))
        <script src="https://www.google.com/recaptcha/api.js?onload=onGoogleRecaptchaLoaded&render=explicit" async
            defer></script>
    @endif

    @yield('script')
</body>

</html>
