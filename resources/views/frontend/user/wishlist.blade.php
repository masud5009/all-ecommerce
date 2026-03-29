@extends('frontend.user.layout')

@section('title', 'My Wishlist | FreshCart')
@section('page', 'user-wishlist')

@section('dashboard-content')
<div class="bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 rounded-3xl border border-emerald-100 bg-white/90 p-6 shadow-xl shadow-emerald-100/40 sm:p-8">
            <h1 class="text-3xl font-bold text-slate-900">My Wishlist</h1>
            <p class="mt-2 text-slate-600">Save your favorite products for later</p>
        </div>

        <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
            <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <h3 class="mt-4 text-lg font-semibold text-slate-900">No items in wishlist</h3>
            <p class="mt-1 text-sm text-slate-600">Start adding products to your wishlist by clicking the heart icon on products.</p>
            <a href="{{ route('frontend.shop') }}" class="mt-6 inline-block rounded-lg bg-green-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-green-700">
                Browse Products
            </a>
        </div>
    </div>
</div>
@endsection
