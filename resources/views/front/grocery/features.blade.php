<section class="mx-auto mb-16 mt-16 max-w-7xl px-4 sm:px-6 lg:px-8" data-reveal>
        <div class="relative overflow-hidden rounded-3xl border border-green-100 bg-white p-8 shadow-sm sm:p-10">
            <div class="pointer-events-none absolute -left-20 top-10 h-44 w-44 rounded-full bg-green-100/60 blur-3xl"
                aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-16 bottom-8 h-36 w-36 rounded-full bg-emerald-100/60 blur-3xl"
                aria-hidden="true"></div>
            <div class="relative">
                <div class="text-center" data-reveal-child>
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-700">
                        {{ @$sectionTitles->features_title ?? 'Freshness section title' }}
                    </p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-900">
                        {{ @$sectionTitles->features_subtitle ?? 'Freshness section sub title' }}
                    </h2>
                    <p class="mt-3 text-sm text-slate-600">
                        {{ @$sectionTitles->features_text ?? 'Freshness section description' }}
                    </p>
                </div>
                <div class="mt-10 grid gap-10 lg:grid-cols-[1fr_auto_1fr] lg:items-center">
                    <div class="order-2 space-y-6 lg:order-none" data-reveal-child>
                        @forelse ($freshnessLeftItems as $item)
                            <div class="flex items-start gap-4">
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                    @if (!empty($item->icon))
                                        <i class="{{ $item->icon }} text-lg"></i>
                                    @else
                                        <i class="fas fa-seedling text-lg"></i>
                                    @endif
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item->title }}</p>
                                    <p class="mt-1 text-xs text-slate-600">{{ $item->text }}</p>
                                </div>
                            </div>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-green-200 bg-white p-6 text-sm text-slate-500">
                                {{ __('No left side feature items found.') }}
                            </div>
                        @endforelse
                    </div>
                    <div class="order-1 mx-auto flex w-64 items-center justify-center sm:w-72 lg:order-none lg:w-80"
                        data-reveal-child>
                        <div class="relative">
                            <div
                                class="absolute inset-0 rounded-full bg-green-50 shadow-[0_25px_80px_rgba(16,185,129,0.2)]">
                            </div>
                            <img src="{{ !empty($sectionTitles?->features_image) ? asset('assets/img/home_section/' . $sectionTitles->features_image) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=640&q=80' }}"
                                alt="Fresh vegetables assortment"
                                class="relative h-auto w-full object-contain drop-shadow-2xl" loading="lazy" width="420"
                                height="420">
                        </div>
                    </div>
                    <div class="order-3 space-y-6 lg:order-none" data-reveal-child>
                        @forelse ($freshnessRightItems as $item)
                            <div class="flex items-start gap-4">
                                <span
                                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-green-100 bg-white text-green-600 shadow-sm">
                                    @if (!empty($item->icon))
                                        <i class="{{ $item->icon }} text-lg"></i>
                                    @else
                                        <i class="fas fa-seedling text-lg"></i>
                                    @endif
                                </span>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $item->title }}</p>
                                    <p class="mt-1 text-xs text-slate-600">{{ $item->text }}</p>
                                </div>
                            </div>
                        @empty
                            <div
                                class="rounded-2xl border border-dashed border-green-200 bg-white p-6 text-sm text-slate-500">
                                {{ __('No right side feature items found.') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
