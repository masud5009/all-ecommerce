    <section class="premium-hero relative overflow-hidden">
        <div class="pointer-events-none absolute inset-0 -z-10" aria-hidden="true">
            <div class="hero-ambient-blob hero-ambient-blob-one"></div>
            <div class="hero-ambient-blob hero-ambient-blob-two"></div>
            <div class="hero-ambient-blob hero-ambient-blob-three"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <div class="relative hero-slider hero-slider-stage" data-hero-slider role="region"
                aria-roledescription="carousel" aria-label="Hero promotions" tabindex="0">
                <div class="hero-controls" aria-label="Hero slide controls">
                    <button type="button" class="hero-nav-btn" aria-label="Previous slide" data-prev>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            aria-hidden="true">
                            <path d="M15 5l-7 7 7 7"></path>
                        </svg>
                    </button>
                    <button type="button" class="hero-nav-btn" aria-label="Next slide" data-next>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            aria-hidden="true">
                            <path d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                @if (count($homeSliders) > 0)
                    @foreach ($homeSliders as $index => $slider)
                        <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-14 hero-slide is-active"
                            data-slide id="hero-slide-1" role="group" aria-roledescription="slide" aria-label="1 of 3"
                            aria-hidden="false">
                            <div class="hero-content">
                                <p
                                    class="inline-flex items-center gap-2 rounded-full bg-emerald-100/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 hero-kicker">
                                    {{ $slider->title }}
                                </p>
                                <h1
                                    class="mt-5 text-4xl font-semibold leading-tight text-slate-900 sm:text-6xl sm:leading-tight lg:text-7xl lg:leading-[1.05] tracking-tight hero-title">
                                    {{-- Groceries That Feel <span class="hero-gradient-text">Premium</span> Every Day --}}
                                    {!! $slider->sub_title !!}
                                </h1>
                                <p class="mt-5 max-w-2xl text-base text-slate-600 sm:text-lg hero-text">
                                    {!! $slider->description !!}
                                </p>
                                <div class="mt-7 flex flex-wrap items-center gap-3 hero-actions">
                                    <a href="{{ $slider->button_url_1 }}"
                                        class="hero-btn-primary rounded-full px-8 py-4 text-sm font-semibold text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                        {{ $slider->button_text_1 }}
                                    </a>
                                    <a href="{{ $slider->button_url_2 }}"
                                        class="hero-btn-secondary rounded-full px-6 py-3.5 text-sm font-semibold text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                        {{ $slider->button_text_2 }}
                                    </a>
                                </div>
                            </div>
                            <div class="relative hero-media">
                                <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
                                <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
                                <div class="hero-media-card group">
                                    <img src="{{ asset('assets/img/home_slider/' . $slider->image) }}"
                                        srcset="{{ asset('assets/img/home_slider/' . $slider->image) }}"
                                        sizes="(min-width: 1024px) 520px, 100vw" width="700" height="520"
                                        alt="Fresh produce assortment on a market table"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                        decoding="async" fetchpriority="high">
                                    <div class="hero-media-gradient" aria-hidden="true"></div>
                                </div>
                                <div class="hero-media-badge hero-media-badge-top">
                                    <p class="hero-media-badge-label">{{ $slider->image_left_badge_title }}</p>
                                    <p class="hero-media-badge-value">{{ $slider->image_left_badge_sub_title }}</p>
                                </div>
                                <div class="hero-media-badge hero-media-badge-bottom">
                                    <p class="hero-media-badge-label">{{ $slider->image_right_badge_title }}</p>
                                    <p class="hero-media-badge-value">{{ $slider->image_right_badge_sub_title }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-14 hero-slide is-active"
                        data-slide id="hero-slide-1" role="group" aria-roledescription="slide" aria-label="1 of 3"
                        aria-hidden="false">
                        <div class="hero-content">
                            <p
                                class="inline-flex items-center gap-2 rounded-full bg-emerald-100/80 px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700 hero-kicker">
                                {{ __('Slider Title') }}
                            </p>
                            <h1
                                class="mt-5 text-4xl font-semibold leading-tight text-slate-900 sm:text-6xl sm:leading-tight lg:text-7xl lg:leading-[1.05] tracking-tight hero-title">
                                {{ __('Slider') }} <span class="hero-gradient-text"> {{ __('Title') }}</span>
                                {{ __('Here') }}
                            </h1>
                            <p class="mt-5 max-w-2xl text-base text-slate-600 sm:text-lg hero-text">
                                {{ __('Slider description goes here. Add some enticing details about your promotion to encourage customers to take action.') }}
                            </p>
                            <div class="mt-7 flex flex-wrap items-center gap-3 hero-actions">
                                <a href="#"
                                    class="hero-btn-primary rounded-full px-8 py-4 text-sm font-semibold text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-300 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                    {{ __('Button Text') }}
                                </a>
                                <a href="#"
                                    class="hero-btn-secondary rounded-full px-6 py-3.5 text-sm font-semibold text-slate-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white">
                                    {{ __('Button Text') }}
                                </a>
                            </div>
                        </div>
                        <div class="relative hero-media">
                            <div class="hero-orbit hero-orbit-one" aria-hidden="true"></div>
                            <div class="hero-orbit hero-orbit-two" aria-hidden="true"></div>
                            <div class="hero-media-card group">
                                <img src="{{ asset('assets/img/home_slider/default.avif') }}"
                                    srcset="{{ asset('assets/img/home_slider/default.avif') }}"
                                    sizes="(min-width: 1024px) 520px, 100vw" width="700" height="520"
                                    alt="Fresh produce assortment on a market table"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    decoding="async" fetchpriority="high">
                                <div class="hero-media-gradient" aria-hidden="true"></div>
                            </div>
                            <div class="hero-media-badge hero-media-badge-top">
                                <p class="hero-media-badge-label">{{ __('Left Badge') }}</p>
                                <p class="hero-media-badge-value">{{ __('Left Badge Sub Title') }}</p>
                            </div>
                            <div class="hero-media-badge hero-media-badge-bottom">
                                <p class="hero-media-badge-label">{{ __('Right Badge') }}</p>
                                <p class="hero-media-badge-value">{{ __('Right Badge Sub Title') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (count($homeSliders) > 0)
                    <div class="mt-10 flex items-center justify-center gap-3" aria-label="Choose slide">
                        <button
                            class="hero-dot bg-green-600 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                            type="button" aria-label="Go to slide 1" aria-controls="hero-slide-1" data-dot
                            aria-current="true"></button>
                        <button
                            class="hero-dot bg-green-200 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                            type="button" aria-label="Go to slide 2" aria-controls="hero-slide-2" data-dot
                            aria-current="false"></button>
                        <button
                            class="hero-dot bg-green-200 transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 focus-visible:ring-offset-2 focus-visible:ring-offset-white"
                            type="button" aria-label="Go to slide 3" aria-controls="hero-slide-3" data-dot
                            aria-current="false"></button>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <div class="mx-auto mt-12 h-px max-w-5xl bg-gradient-to-r from-transparent via-green-200 to-transparent"></div>
