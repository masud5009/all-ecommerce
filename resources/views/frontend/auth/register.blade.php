@extends('front.layout')

@section('title', 'Create Account | FreshCart')
@section('page', 'signup')

@section('content')
<section class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-16 bg-gradient-to-br from-green-50 via-white to-emerald-50">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="rounded-3xl border border-green-100 bg-white/90 backdrop-blur shadow-2xl shadow-green-100/40 px-8 py-10">

            {{-- Logo --}}
            <div class="flex flex-col items-center mb-8">
                <a href="{{ route('frontend.index') }}" class="flex items-center gap-2 text-2xl font-bold text-green-700 mb-2">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 14c6-1 10-5 12-10 3 5 4 10 3 14-2 6-10 8-13 3-1-2-1-5-1-7Z"/>
                            <path d="M7 15c3-2 6-5 9-10"/>
                        </svg>
                    </span>
                    FreshCart
                </a>
                <h1 class="text-xl font-semibold text-slate-800 mt-1">Create your account</h1>
                <p class="text-sm text-slate-500 mt-1">Join thousands of happy shoppers today</p>
            </div>

            {{-- Success message --}}
            @if (session('success'))
                <div id="register-success" class="mb-5 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @else
                <div id="register-success" class="mb-5 hidden items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"></div>
            @endif

            <div id="register-general-error" class="mb-5 hidden items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            {{-- Form --}}
            <form id="register-form" action="{{ route('user.signup_submit') }}" method="POST" novalidate>
                @csrf

                {{-- Username --}}
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-slate-700 mb-1.5">Username</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z"/></svg>
                        </span>
                        <input id="username" name="username" type="text"
                            value="{{ old('username') }}"
                            placeholder="johndoe"
                            autocomplete="username"
                            class="w-full rounded-xl border @error('username') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror py-3 pl-10 pr-4 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                    </div>
                    @error('username')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email address</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z"/><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z"/></svg>
                        </span>
                        <input id="email" name="email" type="email"
                            value="{{ old('email') }}"
                            placeholder="you@example.com"
                            autocomplete="email"
                            class="w-full rounded-xl border @error('email') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror py-3 pl-10 pr-4 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                    </div>
                    @error('email')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                        </span>
                        <input id="password" name="password" type="password"
                            placeholder="Minimum 8 characters"
                            autocomplete="new-password"
                            class="w-full rounded-xl border @error('password') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror py-3 pl-10 pr-11 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                        <button type="button" id="toggle-password"
                            class="absolute inset-y-0 right-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                            aria-label="Toggle password visibility">
                            <svg id="eye-icon" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                        </span>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Re-enter your password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                            <svg class="h-3.5 w-3.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password strength indicator --}}
                <div class="mb-6 -mt-4">
                    <div class="flex gap-1 mt-2" id="strength-bars">
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-1"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-2"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-3"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-4"></div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400" id="strength-label"></p>
                </div>

                @include('front.partials.google-recaptcha', [
                    'wrapperClass' => 'mb-6',
                ])

                {{-- Submit --}}
                <button type="submit" id="register-submit-btn"
                    class="w-full rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 active:translate-y-0">
                    Create account
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
                <div class="relative flex justify-center text-xs"><span class="bg-white px-3 text-slate-400">Already have an account?</span></div>
            </div>

            <a href="{{ route('user.login') }}"
                class="flex w-full items-center justify-center rounded-xl border border-green-200 bg-green-50 px-5 py-3 text-sm font-semibold text-green-700 transition hover:border-green-300 hover:bg-green-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200">
                Sign in instead
            </a>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script src="{{ asset('assets/front/js/auth.js') }}"></script>
@endsection
