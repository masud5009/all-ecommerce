@extends('front.layout')

@section('title', 'Forgot Password | FreshCart')
@section('page', 'forget-password')

@section('content')
<section class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-16 bg-gradient-to-br from-green-50 via-white to-emerald-50">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="rounded-3xl border border-green-100 bg-white/90 backdrop-blur shadow-2xl shadow-green-100/40 px-8 py-10">

            {{-- Icon & heading --}}
            <div class="flex flex-col items-center mb-8">
                <a href="{{ route('frontend.index') }}" class="flex items-center gap-2 text-2xl font-bold text-green-700 mb-4">
                    @if (!empty($websiteInfo->website_logo))
                        <img src="{{ asset('assets/front/img/' . $websiteInfo->website_logo) }}"
                            alt="{{ $websiteInfo->website_title ?? config('app.name') }}"
                            class="h-12 w-auto max-w-[180px] object-contain">
                    @else
                        {{ $websiteInfo->website_title ?? config('app.name') }}
                    @endif
                </a>

                {{-- Key icon --}}
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-amber-50 border border-amber-100 mb-3">
                    <svg class="h-8 w-8 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
                    </svg>
                </div>

                <h1 class="text-xl font-semibold text-slate-800">Forgot your password?</h1>
                <p class="text-sm text-slate-500 mt-1.5 text-center max-w-xs">
                    No worries — enter your email and we'll send you a reset link.
                </p>
            </div>

            {{-- Success / Error messages --}}
            @if (session('success'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('user.forget_password.send_email') }}" method="POST" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-6">
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

                @include('front.partials.google-recaptcha', [
                    'wrapperClass' => 'mb-6',
                ])

                {{-- Submit --}}
                <button type="submit"
                    class="w-full rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 active:translate-y-0">
                    Send reset link
                </button>
            </form>

            {{-- Back to login --}}
            <div class="mt-6 flex items-center justify-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd"/></svg>
                <a href="{{ route('user.login') }}" class="font-medium text-green-600 hover:text-green-700 hover:underline">Back to sign in</a>
            </div>
        </div>

        {{-- Info note --}}
        <div class="mt-5 flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-700">
            <svg class="mt-0.5 h-4 w-4 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/></svg>
            <p>If the email address is registered, you will receive a password reset link shortly. Please check your spam folder too.</p>
        </div>
    </div>
</section>
@endsection
