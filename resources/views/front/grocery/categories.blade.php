    @php
        $categorySectionTitle = trim((string) ($sectionTitles->category_title ?? ''));
        if ($categorySectionTitle === '' || $categorySectionTitle === 'Categories section title') {
            $categorySectionTitle = __('Browse By Categories');
        }

        $categoryRingColors = [
            '232 139 124',
            '125 203 68',
            '174 207 230',
            '253 188 148',
            '237 158 99',
            '124 242 115',
            '235 91 121',
        ];
    @endphp

    <section id="categories" class="mx-auto mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" aria-labelledby="category-heading"
        data-reveal>
        <div class="flex items-center justify-between gap-4" data-reveal-child>
            <div>
                <h2 id="category-heading" class="text-4xl font-bold tracking-normal text-slate-900 sm:text-5xl">
                    {{ $categorySectionTitle }}
                </h2>
            </div>
            @if (count($homeCategories) > 4)
                <div class="hidden items-center gap-2 sm:flex">
                    <button type="button" aria-label="Scroll categories previous" data-category-prev
                        class="inline-flex h-9 w-9 items-center justify-center rounded bg-emerald-800 text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-900 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                            aria-hidden="true">
                            <path d="M15 6l-6 6 6 6"></path>
                        </svg>
                    </button>
                    <button type="button" aria-label="Scroll categories next" data-category-next
                        class="inline-flex h-9 w-9 items-center justify-center rounded bg-emerald-950 text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-emerald-900 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                            aria-hidden="true">
                            <path d="M9 6l6 6-6 6"></path>
                        </svg>
                    </button>
                </div>
            @endif
        </div>
        <div class="category-scroll mt-10 flex gap-8 overflow-x-auto pb-4 scroll-px-4 scroll-smooth snap-x snap-mandatory sm:gap-10 lg:gap-12"
            data-category-track>
            @forelse ($homeCategories as $index => $category)
                @php
                    $isCategoryImage =
                        !empty($category->icon) &&
                        preg_match('/\.(jpe?g|png|webp|svg|avif|gif)$/i', $category->icon);
                    $categoryImageUrl = $isCategoryImage
                        ? asset('assets/img/product/category/' . $category->icon)
                        : null;
                    $categoryProductsCount = $category->productContent->count();
                    $ringColor = $categoryRingColors[$index % count($categoryRingColors)];
                @endphp
                <a href="{{ route('frontend.shop', ['category' => $category->id]) }}"
                    aria-label="Browse {{ $category->name }}" data-reveal-child
                    class="group flex min-w-[140px] flex-col items-center text-center transition duration-300 hover:-translate-y-1 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white sm:min-w-[160px] lg:min-w-[174px] snap-start">
                    <div class="brush-ring flex h-32 w-32 items-center justify-center sm:h-36 sm:w-36"
                        style="--category-ring-rgb: {{ $ringColor }};">
                        <span
                            class="relative z-[1] flex h-[88px] w-[88px] items-center justify-center overflow-hidden rounded-full bg-white shadow-sm ring-1 ring-slate-100 transition duration-300 group-hover:scale-105 sm:h-[96px] sm:w-[96px]">
                            @if ($categoryImageUrl)
                                <img src="{{ $categoryImageUrl }}" alt="{{ $category->name }}" loading="lazy"
                                    class="h-full w-full object-cover">
                            @elseif (!empty($category->icon))
                                <i class="{{ $category->icon }} text-[34px] leading-none text-emerald-700"
                                    aria-hidden="true"></i>
                            @else
                                <svg class="h-10 w-10 text-emerald-700" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M5 12c6 0 8 3 8 7"></path>
                                    <path d="M13 19c0-8 3-12 8-14"></path>
                                    <path d="M5 12c0-4 2-7 7-7 2 0 4 .5 6 1.5"></path>
                                </svg>
                            @endif
                        </span>
                    </div>
                    <p class="mt-4 line-clamp-2 min-h-[2rem] text-xl font-bold leading-6 text-emerald-950 transition group-hover:text-green-800">
                        {{ $category->name }}
                    </p>
                    <span class="mt-2 text-base font-semibold text-slate-400">
                        {{ $categoryProductsCount }} {{ $categoryProductsCount === 1 ? __('Item') : __('Items') }}
                    </span>
                </a>
            @empty
                <div class="w-full rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500"
                    data-reveal-child>
                    {{ __('NO CATEORIES FOUND!') }}
                </div>
            @endforelse
        </div>
    </section>
