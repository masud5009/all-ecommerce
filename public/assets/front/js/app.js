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

    /*========================== Quick View Start ===========================*/
    const initQuickView = () => {
        const modal = qs('[data-quickview-modal]');
        const backdrop = qs('[data-quickview-backdrop]');
        const content = qs('[data-quickview-content]');

        if (!modal || !content) return;

        const openModal = () => {
            modal.classList.remove('hidden');
            backdrop?.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            backdrop?.classList.add('hidden');
            document.body.style.overflow = '';
            // Reset content to loading state
            content.innerHTML = '<div class="flex items-center justify-center py-20"><div class="h-10 w-10 animate-spin rounded-full border-4 border-green-200 border-t-green-600"></div></div>';
        };

        const loadProduct = async (productId) => {
            openModal();
            try {
                const response = await fetch(`/shop/quick-view/${productId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const data = await response.json();
                if (data.success && data.html) {
                    content.innerHTML = data.html;
                    // Set product data from JSON (script tags in innerHTML don't execute)
                    window.quickViewProductData = data.product;
                    initQuickViewActions();
                } else {
                    content.innerHTML = '<div class="text-center py-20 text-slate-500">Product not found</div>';
                }
            } catch (error) {
                console.error('Quick view error:', error);
                content.innerHTML = '<div class="text-center py-20 text-slate-500">Error loading product</div>';
            }
        };

        // Quick view actions (quantity, add to cart, magnify)
        const initQuickViewActions = () => {
            const qtyInput = qs('[data-quickview-qty]', content);
            const decBtn = qs('[data-quickview-qty-dec]', content);
            const incBtn = qs('[data-quickview-qty-inc]', content);
            const addBtn = qs('[data-quickview-add-cart]', content);

            // Initialize magnify for quickview
            const magnifyContainer = qs('[data-magnify]', content);
            if (magnifyContainer) {
                const img = qs('[data-magnify-image]', magnifyContainer) || qs('img', magnifyContainer);
                if (img) {
                    const setBackground = () => {
                        const source = img.currentSrc || img.src;
                        if (source) magnifyContainer.style.backgroundImage = `url("${source}")`;
                    };

                    const updatePosition = (e) => {
                        const rect = magnifyContainer.getBoundingClientRect();
                        const x = Math.max(0, Math.min(100, ((e.clientX - rect.left) / rect.width) * 100));
                        const y = Math.max(0, Math.min(100, ((e.clientY - rect.top) / rect.height) * 100));
                        magnifyContainer.style.backgroundPosition = `${x}% ${y}%`;
                    };

                    magnifyContainer.addEventListener('pointerenter', (e) => {
                        if (e.pointerType === 'touch') return;
                        setBackground();
                        magnifyContainer.classList.add('is-magnifying');
                        updatePosition(e);
                    });

                    magnifyContainer.addEventListener('pointermove', (e) => {
                        if (magnifyContainer.classList.contains('is-magnifying')) updatePosition(e);
                    });

                    magnifyContainer.addEventListener('pointerleave', () => {
                        magnifyContainer.classList.remove('is-magnifying');
                        magnifyContainer.style.backgroundImage = '';
                        magnifyContainer.style.backgroundPosition = '';
                    });

                    img.addEventListener('load', () => {
                        if (magnifyContainer.classList.contains('is-magnifying')) setBackground();
                    });
                }
            }

            if (decBtn && qtyInput) {
                decBtn.addEventListener('click', () => {
                    let val = parseInt(qtyInput.value) || 1;
                    if (val > 1) qtyInput.value = val - 1;
                });
            }

            if (incBtn && qtyInput) {
                incBtn.addEventListener('click', () => {
                    let val = parseInt(qtyInput.value) || 1;
                    if (val < 99) qtyInput.value = val + 1;
                });
            }

            if (addBtn) {
                addBtn.addEventListener('click', () => {
                    const productData = window.quickViewProductData;
                    if (!productData) return;

                    const quantity = parseInt(qtyInput?.value) || 1;
                    const selectedRadio = qs('input[name="quickviewUnit"]:checked', content);

                    let variantId = null;
                    let variantLabel = null;
                    let price = productData.units?.[0]?.price || 0;

                    if (selectedRadio) {
                        const index = parseInt(selectedRadio.value);
                        const unit = productData.units?.[index];
                        if (unit) {
                            variantId = unit.variant_id || null;
                            variantLabel = unit.label;
                            price = unit.price;
                        }
                    } else if (productData.units?.[0]) {
                        variantId = productData.units[0].variant_id || null;
                        variantLabel = productData.units[0].label;
                        price = productData.units[0].price;
                    }

                    // Use cart.js add function
                    if (window.FreshCart && window.FreshCart.cart && window.FreshCart.cart.add) {
                        window.FreshCart.cart.add(productData.id, quantity, variantId, variantLabel, price);
                        closeModal();
                    }
                });
            }
        };

        // Event delegation for quick view buttons
        document.addEventListener('click', (e) => {
            // Open quick view
            const quickViewBtn = e.target.closest('[data-action="quick-view"]');
            if (quickViewBtn) {
                e.preventDefault();
                const productId = quickViewBtn.dataset.productId;
                if (productId) loadProduct(productId);
            }

            // Close quick view
            if (e.target.closest('[data-quickview-close]') || e.target === backdrop) {
                closeModal();
            }
        });

        // Close on Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    };

    initQuickView();
    /*========================== Quick View End ===========================*/


})();
