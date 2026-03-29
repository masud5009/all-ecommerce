@extends('frontend.user.layout')

@section('title', 'Change Password | FreshCart')
@section('page', 'user-change-password')

@section('dashboard-content')
<div class="bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
    <div class="mx-auto w-full max-w-2xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 rounded-3xl border border-emerald-100 bg-white/90 p-6 shadow-xl shadow-emerald-100/40 sm:p-8">
            <h1 class="text-3xl font-bold text-slate-900">Change Password</h1>
            <p class="mt-2 text-slate-600">Update your password to keep your account secure</p>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                    <p class="text-sm font-semibold text-red-800">Please fix the following errors:</p>
                    <ul class="mt-2 list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm text-red-700">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
                    <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                </div>
            @endif

            <form action="{{ route('user.update_password') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-900">Current Password</label>
                    <input type="password" name="current_password" id="current_password"
                        class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                        required>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-semibold text-slate-900">New Password</label>
                    <input type="password" name="new_password" id="new_password"
                        class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                        required>
                    <p class="mt-2 text-xs text-slate-600">
                        Password must be at least 8 characters and contain uppercase, lowercase, number, and special character.
                    </p>
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-900">
                        Confirm New Password
                    </label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                        class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                        required>
                    @error('new_password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Requirements -->
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <p class="text-sm font-semibold text-amber-900">Password Requirements:</p>
                    <ul class="mt-2 space-y-1 text-sm text-amber-800">
                        <li class="flex items-center gap-2">
                            <span class="text-xs">✓</span>
                            At least 8 characters long
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-xs">✓</span>
                            Contains at least one uppercase letter
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-xs">✓</span>
                            Contains at least one lowercase letter
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-xs">✓</span>
                            Contains at least one number
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-xs">✓</span>
                            Contains at least one special character (!@#$%^&*)
                        </li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-lg bg-green-600 px-4 py-2.5 font-medium text-white transition hover:bg-green-700">
                        Update Password
                    </button>
                    <a href="{{ route('user.dashboard') }}" class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-center font-medium text-slate-900 transition hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
