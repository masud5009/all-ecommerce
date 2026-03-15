@extends('front.layout')
@section('title', 'Shop | FreshCart')
@section('page', 'shop')

@section('content')
    <!-- Shop Header -->
    <section class="bg-gradient-to-br from-green-50 via-white to-emerald-50 py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                     {{ __('Our') }} <span class="text-green-600"> {{ __('Products') }}</span>
                </h1>
                <p class="mt-4 text-base text-slate-600 sm:text-lg">
                    Fresh groceries, premium quality, delivered to your doorstep
                </p>
            </div>

            <!-- Search Bar -->
            <form action="{{ route('frontend.shop') }}" method="GET" class="mt-8 flex justify-center">
                <div class="relative w-full max-w-xl">
                    <input type="search" name="search" value="{{ $filters['search'] ?? '' }}"
                        placeholder="Search products..."
                        class="w-full rounded-full border border-green-200 bg-white py-4 pl-12 pr-32 text-sm shadow-sm outline-none transition focus:border-green-400 focus:ring-2 focus:ring-green-200">
                    <span class="pointer-events-none absolute left-4 top-4 text-slate-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="M20 20l-3.5-3.5"></path>
                        </svg>
                    </span>
                    @if(!empty($filters['category']))
                        <input type="hidden" name="category" value="{{ $filters['category'] }}">
                    @endif
                    @if(!empty($filters['sort']))
                        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                    @endif
                    <button type="submit"
                        class="absolute right-2 top-2 rounded-full bg-green-600 px-6 py-2 text-sm font-semibold text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Shop Content -->
    <section class="py-10 sm:py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Sidebar Filters -->
                <aside class="hidden lg:block">
                    <div class="sticky top-24 space-y-8">
                        <!-- Categories -->
                        <div class="rounded-2xl border border-green-100 bg-white p-6 shadow-sm">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-900">Categories</h3>
                            <ul class="mt-4 space-y-2">
                                <li>
                                    <a href="{{ route('frontend.shop', array_merge($filters, ['category' => null])) }}"
                                        class="flex items-center justify-between rounded-xl px-3 py-2 text-sm transition {{ empty($filters['category']) ? 'bg-green-100 text-green-700 font-medium' : 'text-slate-600 hover:bg-green-50 hover:text-green-700' }}">
                                        All Products
                                        <span class="text-xs text-slate-400">{{ $products->total() }}</span>
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('frontend.shop', array_merge($filters, ['category' => $category->id])) }}"
                                            class="flex items-center justify-between rounded-xl px-3 py-2 text-sm transition {{ ($filters['category'] ?? '') == $category->id ? 'bg-green-100 text-green-700 font-medium' : 'text-slate-600 hover:bg-green-50 hover:text-green-700' }}">
                                            {{ $category->name }}
                                            <span class="text-xs text-slate-400">{{ $category->productContent->count() }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Price Filter -->
                        <div class="rounded-2xl border border-green-100 bg-white p-6 shadow-sm">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-900">Price Range</h3>
                            <form action="{{ route('frontend.shop') }}" method="GET" class="mt-4 space-y-4">
                                @if(!empty($filters['category']))
                                    <input type="hidden" name="category" value="{{ $filters['category'] }}">
                                @endif
                                @if(!empty($filters['search']))
                                    <input type="hidden" name="search" value="{{ $filters['search'] }}">
                                @endif
                                @if(!empty($filters['sort']))
                                    <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                                @endif
                                <div class="flex gap-3">
                                    <div class="flex-1">
                                        <label class="sr-only">Min Price</label>
                                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}"
                                            placeholder="Min"
                                            class="w-full rounded-lg border border-green-100 px-3 py-2 text-sm outline-none focus:border-green-400 focus:ring-2 focus:ring-green-200">
                                    </div>
                                    <div class="flex-1">
                                        <label class="sr-only">Max Price</label>
                                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}"
                                            placeholder="Max"
                                            class="w-full rounded-lg border border-green-100 px-3 py-2 text-sm outline-none focus:border-green-400 focus:ring-2 focus:ring-green-200">
                                    </div>
                                </div>
                                <button type="submit"
                                    class="w-full rounded-lg bg-green-600 py-2 text-sm font-medium text-white transition hover:bg-green-700">
                                    Apply Filter
                                </button>
                            </form>
                        </div>

                        <!-- Clear Filters -->
                        @if(!empty($filters['category']) || !empty($filters['search']) || !empty($filters['min_price']) || !empty($filters['max_price']))
                            <a href="{{ route('frontend.shop') }}"
                                class="flex items-center justify-center gap-2 rounded-xl border border-red-200 bg-red-50 py-3 text-sm font-medium text-red-600 transition hover:bg-red-100">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M18 6L6 18M6 6l12 12"></path>
                                </svg>
                                Clear All Filters
                            </a>
                        @endif
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="lg:col-span-3">
                    <!-- Mobile Filter Toggle & Sort -->
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <button type="button" id="mobile-filter-btn"
                                class="flex items-center gap-2 rounded-xl border border-green-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-green-50 lg:hidden">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                Filters
                            </button>
                            <p class="text-sm text-slate-600">
                                Showing <span class="font-medium text-slate-900">{{ $products->count() }}</span> of
                                <span class="font-medium text-slate-900">{{ $products->total() }}</span> products
                            </p>
                        </div>

                        <!-- Sort Dropdown -->
                        <form action="{{ route('frontend.shop') }}" method="GET" id="sort-form">
                            @if(!empty($filters['category']))
                                <input type="hidden" name="category" value="{{ $filters['category'] }}">
                            @endif
                            @if(!empty($filters['search']))
                                <input type="hidden" name="search" value="{{ $filters['search'] }}">
                            @endif
                            @if(!empty($filters['min_price']))
                                <input type="hidden" name="min_price" value="{{ $filters['min_price'] }}">
                            @endif
                            @if(!empty($filters['max_price']))
                                <input type="hidden" name="max_price" value="{{ $filters['max_price'] }}">
                            @endif
                            <select name="sort" onchange="document.getElementById('sort-form').submit()"
                                class="rounded-xl border border-green-200 bg-white px-4 py-2 text-sm text-slate-700 shadow-sm focus:border-green-400 focus:ring-2 focus:ring-green-200">
                                <option value="latest" {{ ($filters['sort'] ?? 'latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ ($filters['sort'] ?? '') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="price_low" {{ ($filters['sort'] ?? '') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ ($filters['sort'] ?? '') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </form>
                    </div>

                    <!-- Products -->
                    @if($products->count() > 0)
                        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach($products as $product)
                                @include('front.home.partials.product-card', ['product' => $product])
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($products->hasPages())
                            <div class="mt-10 flex justify-center">
                                <nav class="flex items-center gap-2">
                                    {{-- Previous --}}
                                    @if($products->onFirstPage())
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-green-100 bg-green-50 text-slate-400 cursor-not-allowed">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M15 18l-6-6 6-6"></path>
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ $products->previousPageUrl() }}"
                                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-green-200 bg-white text-slate-600 transition hover:bg-green-50 hover:text-green-700">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M15 18l-6-6 6-6"></path>
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                                        @if($page == $products->currentPage())
                                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-600 text-sm font-semibold text-white">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="flex h-10 w-10 items-center justify-center rounded-xl border border-green-200 bg-white text-sm font-medium text-slate-600 transition hover:bg-green-50 hover:text-green-700">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if($products->hasMorePages())
                                        <a href="{{ $products->nextPageUrl() }}"
                                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-green-200 bg-white text-slate-600 transition hover:bg-green-50 hover:text-green-700">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 6l6 6-6 6"></path>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="flex h-10 w-10 items-center justify-center rounded-xl border border-green-100 bg-green-50 text-slate-400 cursor-not-allowed">
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M9 6l6 6-6 6"></path>
                                            </svg>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        @endif
                    @else
                        <!-- No Products Found -->
                        <div class="flex flex-col items-center justify-center py-20 text-center">
                            <div class="flex h-24 w-24 items-center justify-center rounded-full bg-green-100">
                                <svg class="h-12 w-12 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M21 21l-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0z"></path>
                                </svg>
                            </div>
                            <h3 class="mt-6 text-xl font-semibold text-slate-900">No products found</h3>
                            <p class="mt-2 text-slate-600">Try adjusting your search or filter to find what you're looking for.</p>
                            <a href="{{ route('frontend.shop') }}"
                                class="mt-6 rounded-full bg-green-600 px-8 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                                View All Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Mobile Filter Modal -->
    <div id="mobile-filter-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50" id="mobile-filter-backdrop"></div>
        <div class="absolute bottom-0 left-0 right-0 max-h-[85vh] overflow-y-auto rounded-t-3xl bg-white p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-slate-900">Filters</h3>
                <button type="button" id="close-mobile-filter"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Categories -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-900 mb-3">Categories</h4>
                <div class="space-y-2">
                    <a href="{{ route('frontend.shop', array_merge($filters, ['category' => null])) }}"
                        class="block rounded-xl px-4 py-3 text-sm transition {{ empty($filters['category']) ? 'bg-green-100 text-green-700 font-medium' : 'bg-green-50 text-slate-600' }}">
                        All Products
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('frontend.shop', array_merge($filters, ['category' => $category->id])) }}"
                            class="block rounded-xl px-4 py-3 text-sm transition {{ ($filters['category'] ?? '') == $category->id ? 'bg-green-100 text-green-700 font-medium' : 'bg-green-50 text-slate-600' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Price Filter -->
            <div class="mb-6">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-slate-900 mb-3">Price Range</h4>
                <form action="{{ route('frontend.shop') }}" method="GET" class="space-y-4">
                    @if(!empty($filters['category']))
                        <input type="hidden" name="category" value="{{ $filters['category'] }}">
                    @endif
                    @if(!empty($filters['search']))
                        <input type="hidden" name="search" value="{{ $filters['search'] }}">
                    @endif
                    @if(!empty($filters['sort']))
                        <input type="hidden" name="sort" value="{{ $filters['sort'] }}">
                    @endif
                    <div class="flex gap-3">
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="Min"
                            class="flex-1 rounded-xl border border-green-100 px-4 py-3 text-sm outline-none focus:border-green-400">
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="Max"
                            class="flex-1 rounded-xl border border-green-100 px-4 py-3 text-sm outline-none focus:border-green-400">
                    </div>
                    <button type="submit"
                        class="w-full rounded-xl bg-green-600 py-3 text-sm font-semibold text-white transition hover:bg-green-700">
                        Apply Price Filter
                    </button>
                </form>
            </div>

            @if(!empty($filters['category']) || !empty($filters['search']) || !empty($filters['min_price']) || !empty($filters['max_price']))
                <a href="{{ route('frontend.shop') }}"
                    class="block w-full rounded-xl border border-red-200 bg-red-50 py-3 text-center text-sm font-medium text-red-600 transition hover:bg-red-100">
                    Clear All Filters
                </a>
            @endif
        </div>
    </div>

    <script>
        // Mobile filter modal toggle
        const filterBtn = document.getElementById('mobile-filter-btn');
        const filterModal = document.getElementById('mobile-filter-modal');
        const filterBackdrop = document.getElementById('mobile-filter-backdrop');
        const closeFilterBtn = document.getElementById('close-mobile-filter');

        if (filterBtn && filterModal) {
            filterBtn.addEventListener('click', () => {
                filterModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });

            const closeModal = () => {
                filterModal.classList.add('hidden');
                document.body.style.overflow = '';
            };

            closeFilterBtn?.addEventListener('click', closeModal);
            filterBackdrop?.addEventListener('click', closeModal);
        }
    </script>
@endsection
