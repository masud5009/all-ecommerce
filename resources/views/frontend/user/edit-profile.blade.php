@extends('frontend.user.layout')

@section('title', 'Edit Profile | FreshCart')
@section('page', 'user-edit-profile')

@section('dashboard-content')
    <div class="bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
        <div class="mx-auto w-full max-w-2xl px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8 rounded-3xl border border-emerald-100 bg-white/90 p-6 shadow-xl shadow-emerald-100/40 sm:p-8">
                <h1 class="text-3xl font-bold text-slate-900"> {{ __('Edit Profile') }}</h1>
                <p class="mt-2 text-slate-600"> {{ __('Update your personal information') }}</p>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
                        <p class="text-sm font-semibold text-red-800"> {{ __('Please fix the following errors') }}:</p>
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

                <form action="{{ route('user.update_profile') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-900">
                            {{ __('Username') }}</label>
                        <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                            required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-900">
                            {{ __('Email Address') }}</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                            required readonly>
                        <p class="mt-1 text-xs text-slate-500">
                            {{ __('Email cannot be changed. Contact support to update.') }}</p>
                    </div>

                    <!-- Phone (optional) -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-900">
                            {{ __('Phone Number') }}</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? '') }}"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                            placeholder="+880 1234567890">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address (optional) -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-slate-900">
                            {{ __('Address') }}</label>
                        <textarea name="address" id="address" rows="3"
                            class="mt-2 block w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-slate-900 transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                            placeholder="Enter your address">{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 rounded-lg bg-green-600 px-4 py-2.5 font-medium text-white transition hover:bg-green-700">
                            {{ __('Save Changes') }}
                        </button>
                        <a href="{{ route('user.dashboard') }}"
                            class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-center font-medium text-slate-900 transition hover:bg-slate-50">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
