    @php
        $heroSlides = collect($homeSliders ?? []);

        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([
                (object) [
                    'title' => __('Fresh Market'),
                    'sub_title' => __('The Power of Fresh Food Every Day'),
                    'description' => __('Choose crisp vegetables, seasonal fruit, pantry essentials, and daily grocery staples from one fresh store.'),
                    'button_text_1' => __('Shop Now'),
                    'button_url_1' => route('frontend.shop'),
                    'button_text_2' => __('Explore Deals'),
                    'button_url_2' => '#categories',
                    'image' => 'default.avif',
                    'image_left_badge_title' => __('Fresh Pick'),
                    'image_left_badge_sub_title' => __('Daily sourced'),
                    'image_right_badge_title' => __('Fast Delivery'),
                    'image_right_badge_sub_title' => __('Same day support'),
                ],
            ]);
        }
    @endphp

    <section class="premium-hero relative overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="hero-slider hero-slider-stage" data-hero-slider data-autoplay="true" role="region"
                aria-roledescription="carousel" aria-label="Hero promotions" tabindex="0">
                @if ($heroSlides->count() > 1)
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
                @endif

                @foreach ($heroSlides as $index => $slider)
                    @php
                        $slideId = 'hero-slide-' . ($index + 1);
                        $imageUrl = asset('assets/img/home_slider/' . $slider->image);
                        $backgroundImageUrl = !empty($slider->background_image)
                            ? asset('assets/img/home_slider/background/' . $slider->background_image)
                            : $imageUrl;
                        $isActive = $index === 0;
                    @endphp

                    <div class="hero-slide {{ $isActive ? 'is-active' : 'hidden' }}" data-slide
                        id="{{ $slideId }}" role="group" aria-roledescription="slide"
                        aria-label="{{ $index + 1 }} of {{ $heroSlides->count() }}"
                        aria-hidden="{{ $isActive ? 'false' : 'true' }}">
                        <img src="{{ $backgroundImageUrl }}" alt="" class="hero-slide-backdrop" aria-hidden="true">

                        <div class="grid min-h-[620px] items-center gap-8 py-16 lg:grid-cols-[1.02fr_0.98fr] lg:py-20">
                            <div class="hero-content">
                                <p class="hero-kicker">
                                    {{ $slider->title }}
                                </p>
                                <h1 class="hero-title">
                                    {!! $slider->sub_title !!}
                                </h1>
                                <div class="hero-copy-row">
                                    <a href="{{ $slider->button_url_1 }}" class="hero-btn-primary">
                                        {{ $slider->button_text_1 }}
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                            aria-hidden="true">
                                            <path d="M7 17 17 7"></path>
                                            <path d="M9 7h8v8"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="hero-media">
                                <div class="hero-product-wrap">
                                    <img src="{{ $imageUrl }}" srcset="{{ $imageUrl }}"
                                        sizes="(min-width: 1024px) 540px, 100vw" width="760" height="620"
                                        alt="{{ strip_tags($slider->sub_title) }}"
                                        class="hero-product-image" decoding="async" fetchpriority="{{ $isActive ? 'high' : 'auto' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                @if ($heroSlides->count() > 1)
                    <div class="hero-dots" aria-label="Choose slide">
                        @foreach ($heroSlides as $index => $slider)
                            <button class="hero-dot" type="button" aria-label="Go to slide {{ $index + 1 }}"
                                aria-controls="hero-slide-{{ $index + 1 }}" data-dot
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
