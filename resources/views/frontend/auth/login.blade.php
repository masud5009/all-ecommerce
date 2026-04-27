@extends('front.layout')

@section('title', 'Login | FreshCart')
@section('page', 'login')

@section('content')
<section class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-16 bg-gradient-to-br from-green-50 via-white to-emerald-50">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="rounded-3xl border border-green-100 bg-white/90 backdrop-blur shadow-2xl shadow-green-100/40 px-8 py-10">

            {{-- Logo --}}
            <div class="flex flex-col items-center mb-8">
                <a href="{{ route('frontend.index') }}" class="flex items-center gap-2 text-2xl font-bold text-green-700 mb-2">
                    @if (!empty($websiteInfo->website_logo))
                        <img src="{{ asset('assets/front/img/' . $websiteInfo->website_logo) }}"
                            alt="{{ $websiteInfo->website_title ?? config('app.name') }}"
                            class="h-12 w-auto max-w-[180px] object-contain">
                    @else
                        {{ $websiteInfo->website_title ?? config('app.name') }}
                    @endif
                </a>
                <h1 class="text-xl font-semibold text-slate-800 mt-1">Welcome back</h1>
                <p class="text-sm text-slate-500 mt-1">Sign in to your account to continue</p>
            </div>

            {{-- Alert messages --}}
            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Ajax error alert (hidden by default) --}}
            <div id="ajax-error" class="mb-5 hidden flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                <span id="ajax-error-msg"></span>
            </div>

            {{-- Form --}}
            <form id="login-form" action="{{ route('user.login_submit') }}" method="POST" novalidate>
                @csrf

                {{-- Email / Username --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email or Username</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z"/><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z"/></svg>
                        </span>
                        <input id="email" name="email" type="text"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            autocomplete="username"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                    </div>
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                        <a href="{{ route('user.forget_password') }}" class="text-xs font-medium text-green-600 hover:text-green-700 hover:underline">Forgot password?</a>
                    </div>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                        </span>
                        <input id="password" name="password" type="password"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-11 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                        <button type="button" id="toggle-password"
                            class="absolute inset-y-0 right-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                            aria-label="Toggle password visibility">
                            <svg id="eye-icon" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2 mb-6">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded border-slate-300 text-green-600 focus:ring-green-200 cursor-pointer">
                    <label for="remember" class="text-sm text-slate-600 cursor-pointer">Remember me for 30 days</label>
                </div>

                @include('front.partials.google-recaptcha', [
                    'wrapperClass' => 'mb-6',
                ])

                {{-- Submit --}}
                <button type="submit" id="login-btn"
                    class="w-full flex items-center justify-center gap-2 rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 active:translate-y-0 disabled:opacity-60 disabled:cursor-not-allowed disabled:translate-y-0">
                    <span id="login-btn-text">Sign in</span>
                    <svg id="login-spinner" class="hidden h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                </button>

                @if (!empty($guestCheckoutEnabled) && !empty($guestCheckoutUrl))
                    <a href="{{ $guestCheckoutUrl }}"
                        class="mt-3 flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-green-300 hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                        {{ __('Continue as Guest') }}
                    </a>
                @endif
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <div class="relative flex justify-center text-xs"><span class="bg-white px-3 text-slate-400">Don't have an account?</span></div>
            </div>

            <a href="{{ route('user.signup') }}"
                class="flex w-full items-center justify-center rounded-xl border border-green-200 bg-green-50 px-5 py-3 text-sm font-semibold text-green-700 transition hover:border-green-300 hover:bg-green-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                Create a free account
            </a>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script src="{{ asset('assets/front/js/auth.js') }}"></script>
@endsection
