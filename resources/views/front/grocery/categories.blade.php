    <section id="categories" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" aria-labelledby="category-heading"
        data-reveal>
        <div class="flex flex-wrap items-center justify-between gap-4" data-reveal-child>
            <div>
                <h2 id="category-heading" class="text-2xl font-semibold text-slate-900">
                    {{ @$sectionTitles->category_title ?? __('Categories section title') }}
                </h2>
            </div>
            @if (count($homeCategories) > 4)
                <div class="hidden items-center gap-2 sm:flex">
                    <button type="button" aria-label="Scroll categories previous" data-category-prev
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-green-100 bg-white text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path d="M15 6l-6 6 6 6"></path>
                        </svg>
                    </button>
                    <button type="button" aria-label="Scroll categories next" data-category-next
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-green-100 bg-white text-green-700 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path d="M9 6l6 6-6 6"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
        <div class="category-scroll mt-8 flex gap-4 overflow-x-auto pb-4 scroll-px-4 scroll-smooth snap-x snap-mandatory"
            data-category-track>
            @forelse ($homeCategories as $category)
                <a href="{{ route('frontend.shop', ['category' => $category->id]) }}"
                    aria-label="Browse {{ $category->name }}" data-reveal-child
                    class="group flex min-w-[150px] flex-col items-center rounded-2xl border border-green-100 bg-white px-4 py-5 text-center shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-300 hover:bg-green-50/70 hover:shadow-[0_16px_32px_rgba(16,185,129,0.18)] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white sm:min-w-[160px] lg:min-w-[170px] snap-start">
                    <div class="brush-ring flex h-20 w-20 items-center justify-center">
                        <span
                            class="flex h-16 w-16 items-center justify-center rounded-full bg-white shadow-sm ring-1 ring-green-100 transition duration-300 group-hover:scale-105">
                            @if (!empty($category->icon))
                                <i class="{{ $category->icon }} text-[26px] leading-none text-emerald-600"
                                    aria-hidden="true"></i>
                            @endif
                        </span>
                    </div>
                    <p class="mt-4 text-sm font-semibold text-emerald-900 transition group-hover:text-green-800">
                        {{ $category->name }}
                    </p>
                    <span
                        class="mt-3 inline-flex items-center rounded-full border border-green-100 bg-green-50 px-2.5 py-1 text-[11px] font-semibold text-green-700">
                        {{ $category->productContent->count() }} {{ __('products') }}
                    </span>
                    <span
                        class="mt-3 inline-flex items-center gap-1 text-[11px] font-semibold text-green-700 opacity-0 transition duration-300 group-hover:translate-x-0 group-hover:opacity-100 translate-x-1"
                        aria-hidden="true">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            aria-hidden="true">
                            <path d="M9 6l6 6-6 6"></path>
                        </svg>
                    </span>
                </a>
            @empty
                <div class="w-full rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500"
                    data-reveal-child>
                    {{ __('NO FEATURED CATEGORY FOUND!') }}
                </div>
            @endforelse
        </div>
    </section>
