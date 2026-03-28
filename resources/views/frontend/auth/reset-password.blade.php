@extends('front.layout')

@section('title', 'Reset Password | FreshCart')
@section('page', 'reset-password')

@section('content')
<section class="min-h-[calc(100vh-160px)] flex items-center justify-center px-4 py-16 bg-gradient-to-br from-green-50 via-white to-emerald-50">
    <div class="w-full max-w-md">

        {{-- Card --}}
        <div class="rounded-3xl border border-green-100 bg-white/90 backdrop-blur shadow-2xl shadow-green-100/40 px-8 py-10">

            {{-- Logo & heading --}}
            <div class="flex flex-col items-center mb-8">
                <a href="{{ route('frontend.index') }}" class="flex items-center gap-2 text-2xl font-bold text-green-700 mb-4">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-100">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 14c6-1 10-5 12-10 3 5 4 10 3 14-2 6-10 8-13 3-1-2-1-5-1-7Z"/>
                            <path d="M7 15c3-2 6-5 9-10"/>
                        </svg>
                    </span>
                    FreshCart
                </a>

                {{-- Shield icon --}}
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-green-50 border border-green-100 mb-3">
                    <svg class="h-8 w-8 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                    </svg>
                </div>

                <h1 class="text-xl font-semibold text-slate-800">Set new password</h1>
                <p class="text-sm text-slate-500 mt-1.5 text-center max-w-xs">
                    Create a strong password you haven't used before.
                </p>
            </div>

            {{-- Flash messages --}}
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

            <div id="reset-success" class="mb-5 hidden items-start gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700"></div>
            <div id="reset-general-error" class="mb-5 hidden items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"></div>

            {{-- Validation errors --}}
            @if ($errors->any())
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-medium mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form id="reset-password-form" action="{{ route('user.reset_password') }}" method="POST" novalidate>
                @csrf

                {{-- New password --}}
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1.5">New Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                        </span>
                        <input id="new_password" name="new_password" type="password"
                            placeholder="Minimum 8 characters"
                            autocomplete="new-password"
                            class="w-full rounded-xl border @error('new_password') border-red-400 bg-red-50 @else border-slate-200 bg-slate-50 @enderror py-3 pl-10 pr-11 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                        <button type="button" id="toggle-new-password"
                            class="absolute inset-y-0 right-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                            aria-label="Toggle password visibility">
                            <svg id="eye-icon-1" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>

                    {{-- Password strength --}}
                    <div class="flex gap-1 mt-2" id="strength-bars">
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-1"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-2"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-3"></div>
                        <div class="h-1 flex-1 rounded-full bg-slate-200 transition-all" id="bar-4"></div>
                    </div>
                    <p class="mt-1 text-xs text-slate-400" id="strength-label"></p>
                </div>

                {{-- Confirm password --}}
                <div class="mb-6">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <span class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-slate-400">
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/></svg>
                        </span>
                        <input id="new_password_confirmation" name="new_password_confirmation" type="password"
                            placeholder="Re-enter your new password"
                            autocomplete="new-password"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-11 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-green-400 focus:bg-white focus:ring-2 focus:ring-green-100">
                        <button type="button" id="toggle-confirm-password"
                            class="absolute inset-y-0 right-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                            aria-label="Toggle confirm password visibility">
                            <svg id="eye-icon-2" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12.5a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/><path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 010-1.186A10.004 10.004 0 0110 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0110 17c-4.257 0-7.893-2.66-9.336-6.41zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>

                    {{-- Match indicator --}}
                    <p class="mt-1.5 text-xs hidden" id="match-indicator"></p>
                </div>

                {{-- Submit --}}
                <button type="submit" id="reset-submit-btn"
                    class="w-full rounded-xl bg-green-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-300 active:translate-y-0">
                    Reset password
                </button>
            </form>

            {{-- Back to login --}}
            <div class="mt-6 flex items-center justify-center gap-2 text-sm text-slate-500">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd"/></svg>
                <a href="{{ route('user.login') }}" class="font-medium text-green-600 hover:text-green-700 hover:underline">Back to sign in</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
    <script src="{{ asset('assets/front/js/auth.js') }}"></script>
@endsection
