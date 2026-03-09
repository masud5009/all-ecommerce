(() => {
    'use strict';
    const qs = (selector, scope = document) => scope.querySelector(selector);
    const qsa = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));

    /*========================== Hero Slider Start ===========================*/
    const initHeroSlider = () => {
        const slider = qs('[data-hero-slider]');
        if (!slider) return;
        let slides = qsa('[data-slide]', slider);
        if (!slides.length) {
            slides = qsa('[data-hero-slide]', slider);
        }
        if (slides.length < 2) return;
        let dots = qsa('[data-dot]', slider);
        if (!dots.length) {
            dots = qsa('[data-hero-dot]', slider);
        }
        const prev = qs('[data-prev]', slider) || qs('[data-hero-prev]', slider);
        const next = qs('[data-next]', slider) || qs('[data-hero-next]', slider);
        const autoPlay = slider.dataset.autoplay === 'true';
        const initialIndex = slides.findIndex((slide) => !slide.classList.contains('hidden'));
        let index = initialIndex >= 0 ? initialIndex : 0;
        let timerId;
        let pointerId = null;
        let startX = 0;
        let startY = 0;
        const swipeThreshold = 50;

        const isInteractive = (target) =>
            !!target.closest('a, button, input, select, textarea, [data-dot], [data-hero-dot]');

        const showSlide = (nextIndex) => {
            index = (nextIndex + slides.length) % slides.length;
            slides.forEach((slide, idx) => {
                const isActive = idx === index;
                slide.classList.toggle('hidden', !isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                slide.classList.toggle('is-active', isActive);
                slide.classList.remove('is-animating');
            });
            const activeSlide = slides[index];
            if (activeSlide) {
                requestAnimationFrame(() => {
                    activeSlide.classList.add('is-animating');
                });
            }
            dots.forEach((dot, idx) => {
                const isActive = idx === index;
                dot.classList.toggle('bg-green-600', isActive);
                dot.classList.toggle('bg-green-200', !isActive);
                dot.setAttribute('aria-current', isActive ? 'true' : 'false');
            });
        };

        const stopAuto = () => {
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
            }
        };

        const startAuto = () => {
            if (!autoPlay) return;
            stopAuto();
            timerId = setInterval(() => {
                showSlide(index + 1);
            }, 6500);
        };

        if (prev) {
            prev.addEventListener('click', () => {
                showSlide(index - 1);
                startAuto();
            });
        }
        if (next) {
            next.addEventListener('click', () => {
                showSlide(index + 1);
                startAuto();
            });
        }
        dots.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                showSlide(idx);
                startAuto();
            });
        });

        slider.addEventListener('keydown', (event) => {
            const target = event.target;
            const tagName = target && target.tagName ? target.tagName.toLowerCase() : '';
            if (
                tagName === 'input' ||
                tagName === 'textarea' ||
                tagName === 'select' ||
                tagName === 'button' ||
                tagName === 'a' ||
                target.isContentEditable
            ) {
                return;
            }
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                showSlide(index + 1);
                startAuto();
            }
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                showSlide(index - 1);
                startAuto();
            }
        });

        slider.addEventListener('pointerdown', (event) => {
            if (event.pointerType === 'mouse' && event.button !== 0) return;
            if (isInteractive(event.target)) return;
            pointerId = event.pointerId;
            startX = event.clientX;
            startY = event.clientY;
            slider.setPointerCapture?.(pointerId);
        });

        slider.addEventListener('pointerup', (event) => {
            if (pointerId === null || event.pointerId !== pointerId) return;
            const deltaX = event.clientX - startX;
            const deltaY = event.clientY - startY;
            pointerId = null;
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > swipeThreshold) {
                showSlide(deltaX < 0 ? index + 1 : index - 1);
                startAuto();
            }
        });

        slider.addEventListener('pointercancel', () => {
            pointerId = null;
        });

        if (autoPlay) {
            slider.addEventListener('mouseenter', stopAuto);
            slider.addEventListener('mouseleave', startAuto);
            slider.addEventListener('focusin', stopAuto);
            slider.addEventListener('focusout', startAuto);
            startAuto();
        }

        showSlide(0);
    };
    /*========================== Hero Slider End ===========================*/

    /*========================== Category Scroll Start ===========================*/
    const initCategoryScroll = () => {
        const track = qs('[data-category-track]');
        if (!track) return;

        const prev = qs('[data-category-prev]');
        const next = qs('[data-category-next]');

        // Calculate scroll amount (80% of visible width, max 420px)
        const getScrollAmount = () => Math.min(420, track.clientWidth * 0.8);

        // Smooth scroll by amount
        const scrollByAmount = (amount) => {
            track.scrollBy({
                left: amount,
                behavior: 'smooth'
            });
        };

        // Previous button click
        prev?.addEventListener('click', () => scrollByAmount(-getScrollAmount()));

        // Next button click
        next?.addEventListener('click', () => scrollByAmount(getScrollAmount()));

        // Mouse wheel horizontal scroll
        track.addEventListener('wheel', (event) => {
            // Allow natural horizontal scroll
            if (Math.abs(event.deltaX) > Math.abs(event.deltaY)) return;

            event.preventDefault();
            track.scrollBy({
                left: event.deltaY,
                behavior: 'smooth'
            });
        }, { passive: false });
    };
    /*========================== Category Scroll End ===========================*/


    initHeroSlider();
    initCategoryScroll();


})();
