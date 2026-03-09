(() => {

  const products = [];

  const storeKey = 'grocery_cart';
  const modalState = {
    element: null,
    activeProductId: null,
    lastFocused: null
  };
  let lastCartCount = null;

  const qs = (selector, scope = document) => scope.querySelector(selector);
  const qsa = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));

  /**
   * Scan product cards in the DOM for data-product-json attributes
   * and populate the products array for quick view, cart, and variation features.
   */
  const initProductsFromDOM = () => {
    document.querySelectorAll('[data-product-json]').forEach((el) => {
      try {
        const raw = JSON.parse(el.dataset.productJson);
        const id = String(raw.id ?? '');
        if (!id || products.some((p) => p.id === id)) return;

        const normalizedUnits =
          Array.isArray(raw.units) && raw.units.length
            ? raw.units.map((unit, idx) => ({
                label: unit.label || `Option ${idx + 1}`,
                price: Number(unit.price || 0),
                oldPrice: Number(unit.oldPrice || 0)
              }))
            : [{ label: '1 unit', price: Number(raw.price || 0), oldPrice: Number(raw.oldPrice || 0) }];

        products.push({
          id,
          name: raw.name || `Product #${id}`,
          category: raw.category || 'Featured',
          rating: Number(raw.rating || 4.7),
          reviews: Number(raw.reviews || 142),
          badge: raw.badge || 'Featured',
          image: raw.image || '',
          images: Array.isArray(raw.images) && raw.images.length ? raw.images : (raw.image ? [raw.image] : []),
          isDeal: Boolean(raw.isDeal),
          popular: true,
          description: raw.description || 'Product from our catalog.',
          nutrition: Array.isArray(raw.nutrition) ? raw.nutrition : ['Fresh stock', 'Quality checked'],
          reviewList: Array.isArray(raw.reviewList) ? raw.reviewList : [{ name: 'Customer', rating: 5, text: 'Great quality product.' }],
          units: normalizedUnits
        });
      } catch (e) { /* skip invalid entries */ }
    });
  };

  const productDetailsRouteTemplate =
    typeof window !== 'undefined' &&
    window.frontRoutes &&
    typeof window.frontRoutes.productDetails === 'string'
      ? window.frontRoutes.productDetails
      : '';

  const getProductDetailsUrl = (productId) => {
    const normalizedId = String(productId ?? '').trim();
    if (!normalizedId) {
      return productDetailsRouteTemplate || '/product-details';
    }

    const encodedId = encodeURIComponent(normalizedId);

    if (productDetailsRouteTemplate) {
      if (productDetailsRouteTemplate.includes('__PRODUCT_ID__')) {
        return productDetailsRouteTemplate.replace('__PRODUCT_ID__', encodedId);
      }

      const separator = productDetailsRouteTemplate.includes('?') ? '&' : '?';
      return `${productDetailsRouteTemplate}${separator}product=${encodedId}`;
    }

    return `/product-details/${encodedId}`;
  };

  const formatCurrency = (value) =>
    new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(value);

  const lockBodyScroll = () => {
    document.body.style.overflow = 'hidden';
  };

  const unlockBodyScroll = () => {
    document.body.style.overflow = '';
  };

  const getCart = () => {
    try {
      return JSON.parse(localStorage.getItem(storeKey)) || [];
    } catch (error) {
      return [];
    }
  };

  const saveCart = (cart) => {
    localStorage.setItem(storeKey, JSON.stringify(cart));
  };

  const findProduct = (id) => products.find((product) => String(product.id) === String(id));
  const findUnit = (product, label) => product.units.find((unit) => unit.label === label) || product.units[0];

  const getCartCount = (cart) => cart.reduce((sum, item) => sum + item.qty, 0);

  const updateCartBadges = () => {
    const cart = getCart();
    const count = getCartCount(cart);
    const shouldPop = lastCartCount !== null && count !== lastCartCount;
    qsa('[data-cart-count]').forEach((el) => {
      el.textContent = count;
      el.classList.toggle('hidden', count === 0);
      if (shouldPop && count > 0) {
        el.classList.remove('badge-pop');
        void el.offsetWidth;
        el.classList.add('badge-pop');
      }
    });
    lastCartCount = count;
  };

  const updateNavActive = () => {
    const page = document.body.dataset.page;
    if (!page) return;
    const pageMap = {
      home: 'home',
      products: 'categories',
      details: 'categories',
      cart: 'shop',
      checkout: 'account'
    };
    const activeNav = pageMap[page] || page;
    qsa('[data-nav]').forEach((link) => {
      if (link.dataset.nav === activeNav) {
        link.classList.add('text-green-700', 'font-semibold', 'bg-green-50');
        link.setAttribute('aria-current', 'page');
      }
    });
  };

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

  const renderStars = (rating) => {
    const rounded = Math.round(rating);
    let stars = '';
    for (let i = 1; i <= 5; i += 1) {
      const filled = i <= rounded;
      stars += `
        <svg class="h-4 w-4 ${filled ? 'text-amber-400' : 'text-slate-200'}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
          <path d="M12 17.3l-6.2 3.7 1.7-7.1L2 9.2l7.3-.6L12 2l2.7 6.6 7.3.6-5.5 4.7 1.7 7.1L12 17.3Z"></path>
        </svg>
      `;
    }
    return stars;
  };

  const setActiveThumb = (thumb) => {
    if (!thumb) return;
    qsa('[data-thumb]').forEach((button) => {
      button.classList.remove('border-green-500', 'ring-2', 'ring-green-200');
      button.classList.add('border-green-100');
      button.setAttribute('aria-pressed', 'false');
    });
    thumb.classList.add('border-green-500', 'ring-2', 'ring-green-200');
    thumb.classList.remove('border-green-100');
    thumb.setAttribute('aria-pressed', 'true');
  };

  const getCartItem = (cart, id, unit) => cart.find((item) => item.id === id && item.unit === unit);

  const addToCart = (id, unit, qty = 1) => {
    const cart = getCart();
    const item = getCartItem(cart, id, unit);
    const amount = Math.max(1, Number(qty) || 1);
    if (item) {
      item.qty += amount;
    } else {
      cart.push({ id, unit, qty: amount });
    }
    saveCart(cart);
    return cart;
  };

  const updateCartItem = (id, unit, delta) => {
    const cart = getCart();
    const item = getCartItem(cart, id, unit);
    if (!item) return cart;
    item.qty += delta;
    if (item.qty <= 0) {
      const index = cart.indexOf(item);
      cart.splice(index, 1);
    }
    saveCart(cart);
    return cart;
  };

  const removeCartItem = (id, unit) => {
    const cart = getCart().filter((item) => !(item.id === id && item.unit === unit));
    saveCart(cart);
    return cart;
  };

  const buildModalUnits = (product, selectedUnit) =>
    product.units
      .map((option, index) => {
        const isChecked = selectedUnit ? option.label === selectedUnit : index === 0;
        return `
          <label class="group relative flex cursor-pointer items-center gap-3">
            <input class="peer sr-only" type="radio" name="modal-unit" value="${option.label}" data-modal-unit data-price="${option.price}" data-old-price="${option.oldPrice || ''}" ${isChecked ? 'checked' : ''}>
            <div class="flex w-full items-center justify-between rounded-2xl border border-green-100 bg-white px-5 py-4 text-sm shadow-sm transition hover:border-green-300 hover:shadow-md peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:ring-2 peer-checked:ring-green-200">
              <div>
                <p class="font-semibold text-slate-900">${option.label}</p>
              </div>
              <div class="text-right">
                <p class="font-semibold text-slate-900">${formatCurrency(option.price)}</p>
                <p class="text-xs text-slate-400 line-through ${option.oldPrice ? '' : 'hidden'}">${option.oldPrice ? formatCurrency(option.oldPrice) : ''}</p>
              </div>
            </div>
          </label>
        `;
      })
      .join('');

  const buildDetailUnits = (product, selectedUnit) =>
    product.units
      .map((option, index) => {
        const isChecked = selectedUnit ? option.label === selectedUnit : index === 0;
        return `
          <label class="group relative flex cursor-pointer items-center gap-3">
            <input class="peer sr-only" type="radio" name="detail-unit" value="${option.label}" data-detail-unit data-price="${option.price}" data-old-price="${option.oldPrice || ''}" ${isChecked ? 'checked' : ''}>
            <div class="flex w-full items-center justify-between rounded-2xl border border-green-100 bg-white px-5 py-4 text-sm shadow-sm transition hover:border-green-300 hover:shadow-md peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:ring-2 peer-checked:ring-green-200">
              <div>
                <p class="font-semibold text-slate-900">${option.label}</p>
              </div>
              <div class="text-right">
                <p class="font-semibold text-slate-900">${formatCurrency(option.price)}</p>
                <p class="text-xs text-slate-400 line-through ${option.oldPrice ? '' : 'hidden'}">${option.oldPrice ? formatCurrency(option.oldPrice) : ''}</p>
              </div>
            </div>
          </label>
        `;
      })
      .join('');

  const updateModalPrice = (modal, option) => {
    if (!modal || !option) return;
    const price = Number(option.dataset.price || 0);
    const oldPrice = Number(option.dataset.oldPrice || 0);
    qs('[data-modal-price]', modal).textContent = formatCurrency(price);
    modal.dataset.unit = option.value;
    const oldEl = qs('[data-modal-old-price]', modal);
    if (oldPrice && oldPrice > price) {
      oldEl.textContent = formatCurrency(oldPrice);
      oldEl.classList.remove('hidden');
    } else {
      oldEl.classList.add('hidden');
    }
  };

  const updateModalQty = (modal, delta) => {
    if (!modal) return;
    const qtyEl = qs('[data-modal-qty]', modal);
    if (!qtyEl) return;
    const current = Number(qtyEl.textContent || 1);
    const next = Math.max(1, current + delta);
    qtyEl.textContent = next;
  };

  const ensureProductModal = () => {
    if (modalState.element) return modalState.element;
    const existing = qs('[data-modal="product"]');
    if (existing) {
      modalState.element = existing;
      return existing;
    }

    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
      <div class="fixed inset-0 z-[100] hidden overflow-y-auto" data-modal="product" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="product-modal-title">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-modal-overlay></div>
        <div class="relative mx-auto flex min-h-screen items-center justify-center px-4 py-6 sm:py-10">
          <div class="relative w-full max-w-5xl overflow-hidden rounded-3xl border border-green-100 bg-white shadow-2xl" data-modal-card>
            <button class="absolute right-4 top-4 z-10 inline-flex h-9 w-9 items-center justify-center rounded-full border border-green-100 bg-white text-slate-500 shadow-sm transition hover:text-green-700" type="button" aria-label="Close" data-modal-close>
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M6 6l12 12"></path>
                <path d="M18 6l-12 12"></path>
              </svg>
            </button>
            <div class="grid gap-6 lg:grid-cols-[1.05fr_1fr]">
              <div class="relative bg-gradient-to-br from-green-50 via-white to-emerald-50 p-6 sm:p-8">
                <div class="magnify overflow-hidden rounded-2xl border border-green-100 bg-white shadow-sm" data-magnify>
                  <img src="" alt="" class="h-64 w-full object-cover sm:h-72" data-modal-image data-magnify-image>
                </div>
                <div class="mt-3 hidden grid-cols-4 gap-2" data-modal-thumbs></div>
              </div>
              <div class="p-6 sm:p-8">
                <h3 class="text-2xl font-semibold text-slate-900" id="product-modal-title" data-modal-name>Product name</h3>
                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500" data-modal-rating></div>
                <p class="mt-4 text-sm text-slate-600" data-modal-description></p>
                <div class="mt-5" data-modal-units-block>
                  <p class="text-sm font-semibold text-slate-900">Choose size</p>
                  <div class="mt-3 grid gap-2" data-modal-units role="radiogroup"></div>
                </div>
                <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                  <div>
                    <p class="text-3xl font-semibold text-slate-900" data-modal-price>$0.00</p>
                    <p class="text-sm text-slate-400 line-through hidden" data-modal-old-price>$0.00</p>
                  </div>
                  <div class="flex flex-wrap items-center gap-3">
                    <div class="flex items-center gap-2 rounded-2xl border border-green-100 bg-white px-3 py-2">
                      <button class="h-8 w-8 rounded-full bg-green-50 text-green-700" type="button" aria-label="Decrease quantity" data-modal-action="dec">-</button>
                      <span class="min-w-[1.5rem] text-center text-sm font-semibold text-slate-700" data-modal-qty>1</span>
                      <button class="h-8 w-8 rounded-full bg-green-600 text-white" type="button" aria-label="Increase quantity" data-modal-action="inc">+</button>
                    </div>
                    <button class="rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" type="button" data-action="add">Add to cart</button>
                  </div>
                </div>
                <div class="mt-5 flex items-center justify-end text-xs text-slate-500">
                  <a href="#" class="font-semibold text-green-700" data-modal-link>View details</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;

    document.body.append(wrapper.firstElementChild);
    modalState.element = qs('[data-modal="product"]');
    return modalState.element;
  };

  const openProductModal = (productId, options = {}) => {
    const product = findProduct(productId);
    if (!product) return;
    const modal = ensureProductModal();
    const selectedUnit = product.units.some((unit) => unit.label === options.unit)
      ? options.unit
      : product.units[0].label;

    modal.dataset.productId = product.id;
    modalState.activeProductId = product.id;
    modalState.lastFocused = document.activeElement;

    qs('[data-modal-name]', modal).textContent = product.name;

    const ratingEl = qs('[data-modal-rating]', modal);
    ratingEl.innerHTML = `
      <span class="flex items-center gap-1">${renderStars(product.rating)}</span>
      <span>${product.rating.toFixed(1)} (${product.reviews} reviews)</span>
    `;

    qs('[data-modal-description]', modal).textContent = product.description;

    const imageEl = qs('[data-modal-image]', modal);
    const images =
      Array.isArray(product.images) && product.images.length
        ? product.images
        : [product.image];

    imageEl.src = images[0];
    imageEl.alt = product.name;

    const thumbsEl = qs('[data-modal-thumbs]', modal);
    if (thumbsEl) {
      if (images.length > 1) {
        thumbsEl.innerHTML = images
          .slice(0, 8)
          .map(
            (src, index) => `
            <button class="group overflow-hidden rounded-xl border ${index === 0 ? 'border-green-500 ring-2 ring-green-200' : 'border-green-100'} bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" data-modal-thumb data-src="${src}" aria-label="View image ${index + 1}" aria-pressed="${index === 0 ? 'true' : 'false'}" type="button">
              <img src="${src}" alt="${product.name} thumbnail ${index + 1}" loading="lazy" decoding="async" class="h-12 w-full object-cover transition duration-300 group-hover:scale-105">
            </button>
          `
          )
          .join('');
        thumbsEl.classList.remove('hidden');
        thumbsEl.classList.add('grid');
      } else {
        thumbsEl.innerHTML = '';
        thumbsEl.classList.add('hidden');
        thumbsEl.classList.remove('grid');
      }
    }

    const hasVariants = product.units.length > 1;
    const unitsBlock = qs('[data-modal-units-block]', modal);
    const unitsEl = qs('[data-modal-units]', modal);
    if (unitsBlock) {
      unitsBlock.classList.toggle('hidden', !hasVariants);
    }
    unitsEl.innerHTML = buildModalUnits(product, selectedUnit);
    const selectedInput = qs('[data-modal-unit]:checked', modal) || qs('[data-modal-unit]', modal);
    updateModalPrice(modal, selectedInput);

    const qtyEl = qs('[data-modal-qty]', modal);
    if (qtyEl) qtyEl.textContent = '1';

    const linkEl = qs('[data-modal-link]', modal);
    linkEl.href = getProductDetailsUrl(product.id);

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    modal.scrollTop = 0;
    lockBodyScroll();

    if (options.focusUnits && hasVariants) {
      const focusTarget = qs('[data-modal-unit]:checked', modal) || qs('[data-modal-unit]', modal);
      if (focusTarget) {
        focusTarget.focus();
      }
    }
  };

  const closeProductModal = () => {
    const modal = modalState.element || qs('[data-modal="product"]');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    unlockBodyScroll();
    if (modalState.lastFocused && typeof modalState.lastFocused.focus === 'function') {
      modalState.lastFocused.focus();
    }
  };

  const initMagnify = () => {
    const magnifyTargets = qsa('[data-magnify]');
    magnifyTargets.forEach((container) => {
      if (container.dataset.magnifyReady) return;
      container.dataset.magnifyReady = 'true';

      const img = qs('[data-magnify-image]', container) || qs('img', container);
      if (!img) return;

      const setBackground = () => {
        const source = img.currentSrc || img.src;
        if (source) {
          container.style.backgroundImage = `url("${source}")`;
        }
      };

      const updatePosition = (event) => {
        const rect = container.getBoundingClientRect();
        const x = ((event.clientX - rect.left) / rect.width) * 100;
        const y = ((event.clientY - rect.top) / rect.height) * 100;
        const clampedX = Math.max(0, Math.min(100, x));
        const clampedY = Math.max(0, Math.min(100, y));
        container.style.backgroundPosition = `${clampedX}% ${clampedY}%`;
      };

      const activate = (event) => {
        setBackground();
        container.classList.add('is-magnifying');
        updatePosition(event);
      };

      const deactivate = () => {
        container.classList.remove('is-magnifying');
        container.style.backgroundImage = '';
        container.style.backgroundPosition = '';
      };

      container.addEventListener('pointerenter', (event) => {
        if (event.pointerType === 'touch') return;
        activate(event);
      });

      container.addEventListener('pointermove', (event) => {
        if (!container.classList.contains('is-magnifying')) return;
        updatePosition(event);
      });

      container.addEventListener('pointerleave', () => {
        deactivate();
      });

      img.addEventListener('load', () => {
        if (container.classList.contains('is-magnifying')) {
          setBackground();
        }
      });
    });
  };

  const initDealsCarousel = () => {
    const slider = qs('[data-deals-slider]');
    if (!slider || slider.dataset.dealsReady === 'true') return;
    slider.dataset.dealsReady = 'true';

    const prev = qs('[data-deals-prev]');
    const next = qs('[data-deals-next]');
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const getScrollAmount = () => {
      const item = slider.firstElementChild;
      if (!item) return Math.max(240, slider.clientWidth * 0.8);
      const gapValue = Number.parseFloat(getComputedStyle(slider).gap || '0');
      const gap = Number.isNaN(gapValue) ? 0 : gapValue;
      return item.getBoundingClientRect().width + gap;
    };

    const scrollByAmount = (direction) => {
      const amount = getScrollAmount() * direction;
      slider.scrollBy({ left: amount, behavior: reduceMotion ? 'auto' : 'smooth' });
    };

    const updateButtons = () => {
      const maxScroll = slider.scrollWidth - slider.clientWidth;
      const atStart = slider.scrollLeft <= 4;
      const atEnd = slider.scrollLeft >= maxScroll - 4;
      if (prev) {
        prev.disabled = atStart;
        prev.setAttribute('aria-disabled', String(atStart));
      }
      if (next) {
        next.disabled = atEnd;
        next.setAttribute('aria-disabled', String(atEnd));
      }
    };

    prev?.addEventListener('click', () => scrollByAmount(-1));
    next?.addEventListener('click', () => scrollByAmount(1));

    slider.addEventListener('keydown', (event) => {
      if (event.target !== slider) return;
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        scrollByAmount(-1);
      }
      if (event.key === 'ArrowRight') {
        event.preventDefault();
        scrollByAmount(1);
      }
    });

    slider.addEventListener(
      'wheel',
      (event) => {
        if (slider.scrollWidth <= slider.clientWidth) return;
        if (Math.abs(event.deltaY) <= Math.abs(event.deltaX)) return;
        slider.scrollBy({ left: event.deltaY, behavior: 'auto' });
        event.preventDefault();
      },
      { passive: false }
    );

    slider.addEventListener('scroll', () => requestAnimationFrame(updateButtons), { passive: true });
    window.addEventListener('resize', updateButtons);
    updateButtons();
  };

  const initStickyHeader = () => {
    const header = qs('[data-header]');
    if (!header) return;
    const onScroll = () => {
      header.classList.toggle('is-scrolled', window.scrollY > 12);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  };

  const initCountdowns = () => {
    const countdowns = qsa('[data-countdown]');
    if (!countdowns.length) return;
    const baseSeconds = Number(countdowns[0].dataset.countdownSeconds || 0);
    let remaining = baseSeconds > 0 ? baseSeconds : 8132;
    const format = (value) => String(value).padStart(2, '0');

    const tick = () => {
      const hours = Math.floor(remaining / 3600);
      const minutes = Math.floor((remaining % 3600) / 60);
      const seconds = remaining % 60;
      const output = `${format(hours)}:${format(minutes)}:${format(seconds)}`;
      countdowns.forEach((node) => {
        node.textContent = output;
      });
      remaining = Math.max(0, remaining - 1);
    };

    tick();
    setInterval(tick, 1000);
  };

  const initScrollReveal = () => {
    const sections = qsa('[data-reveal]');
    if (!sections.length) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    document.body.dataset.revealReady = 'true';

    sections.forEach((section) => {
      qsa('[data-reveal-child]', section).forEach((child, index) => {
        child.style.setProperty('--reveal-delay', `${index * 90}ms`);
      });
    });

    const observer = new IntersectionObserver(
      (entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            obs.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.2, rootMargin: '0px 0px -10% 0px' }
    );

    sections.forEach((section) => observer.observe(section));
  };

  const updateDetailPrice = (detail) => {
    const select = qs('[data-detail-unit-select]', detail);
    if (!select || !select.selectedOptions || !select.selectedOptions.length) return;
    const option = select.selectedOptions[0];
    const price = Number(option.dataset.price || 0);
    const oldPrice = Number(option.dataset.oldPrice || 0);
    const priceEl = qs('[data-detail-price]', detail);
    if (priceEl) {
      priceEl.textContent = formatCurrency(price);
    }
    const oldEl = qs('[data-detail-old-price]', detail);
    if (!oldEl) return;
    if (oldPrice && oldPrice > price) {
      oldEl.textContent = formatCurrency(oldPrice);
      oldEl.classList.remove('hidden');
    } else {
      oldEl.classList.add('hidden');
    }
  };

  const syncDetailState = (detail) => {
    const id = detail.dataset.productId;
    const unit = qs('[data-detail-unit-select]', detail).value;
    const cart = getCart();
    const item = getCartItem(cart, id, unit);
    const addBtn = qs('[data-action="add"]', detail);
    const stepper = qs('[data-qty-stepper]', detail);
    const qtyEl = qs('[data-qty]', stepper);

    if (item) {
      addBtn.classList.add('hidden');
      stepper.classList.remove('hidden');
      qtyEl.textContent = item.qty;
    } else {
      addBtn.classList.remove('hidden');
      stepper.classList.add('hidden');
    }
  };

  const renderProductDetails = () => {
    const detail = qs('[data-product-detail]');
    if (!detail) return;
    const serverProduct =
      typeof window !== 'undefined' &&
      window.serverProductDetail &&
      typeof window.serverProductDetail === 'object'
        ? window.serverProductDetail
        : null;

    let sourceProduct = serverProduct;

    if (!sourceProduct) {
      const params = new URLSearchParams(window.location.search);
      const pathMatch = window.location.pathname.match(/\/product-details\/([^/?#]+)/i);
      const pathProductId = pathMatch ? decodeURIComponent(pathMatch[1]) : null;
      const productId = params.get('product') || params.get('id') || pathProductId;
      sourceProduct = productId ? findProduct(productId) : null;
    }

    if (!sourceProduct) {
      detail.innerHTML = `
        <div class="rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500">
          Product information is unavailable.
        </div>
      `;
      return;
    }

    const fallbackUnit = {
      label: '1 unit',
      price: Number(sourceProduct.price || sourceProduct.current_price || 0),
      oldPrice: Number(sourceProduct.oldPrice || sourceProduct.previous_price || 0)
    };
    const sourceImages =
      Array.isArray(sourceProduct.images) && sourceProduct.images.length
        ? sourceProduct.images.filter((src) => typeof src === 'string' && src.trim() !== '')
        : [];
    const primaryImage =
      (typeof sourceProduct.image === 'string' && sourceProduct.image.trim() !== ''
        ? sourceProduct.image
        : sourceImages[0]) || '';

    if (!primaryImage) {
      detail.innerHTML = `
        <div class="rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500">
          Product image is unavailable.
        </div>
      `;
      return;
    }

    const product = {
      ...sourceProduct,
      rating: Number(sourceProduct.rating || 4.7),
      reviews: Number(sourceProduct.reviews || 142),
      image: primaryImage,
      images: sourceImages.length ? sourceImages : [primaryImage],
      summary: sourceProduct.summary || sourceProduct.description || '',
      description: sourceProduct.description || sourceProduct.summary || '',
      units:
        Array.isArray(sourceProduct.units) && sourceProduct.units.length
          ? sourceProduct.units
          : [fallbackUnit],
      nutrition:
        Array.isArray(sourceProduct.nutrition) && sourceProduct.nutrition.length
          ? sourceProduct.nutrition
          : ['Fresh stock', 'Quality checked'],
      reviewList:
          Array.isArray(sourceProduct.reviewList) && sourceProduct.reviewList.length
            ? sourceProduct.reviewList
            : [{ name: 'Customer', rating: 5, text: 'Great quality product.' }]
    };
    const normalizedId = String(product.id || '');
    if (normalizedId && !findProduct(normalizedId)) {
      products.unshift({
        id: normalizedId,
        name: product.name || `Product #${normalizedId}`,
        category: product.category || 'Featured',
        rating: Number(product.rating || 4.7),
        reviews: Number(product.reviews || 142),
        badge: product.badge || product.category || 'Featured',
        image: product.image,
        images: product.images,
        isDeal: Boolean(product.isDeal),
        popular: Boolean(product.popular),
        description: product.description || product.summary || '',
        nutrition: Array.isArray(product.nutrition) ? product.nutrition : [],
        reviewList: Array.isArray(product.reviewList) ? product.reviewList : [],
        units: Array.isArray(product.units) && product.units.length ? product.units : [fallbackUnit]
      });
    }

    detail.dataset.productId = normalizedId;

    const nameEl = qs('[data-detail-name]');
    nameEl.textContent = product.name;
    qs('[data-detail-breadcrumb]').textContent = product.name;

    const categoryEl = qs('[data-detail-category]');
    if (categoryEl) {
      categoryEl.textContent = product.category;
    }

    const badgeEl = qs('[data-detail-badge]');
    if (badgeEl) {
      if (product.badge) {
        badgeEl.textContent = product.badge;
        badgeEl.classList.remove('hidden');
      } else {
        badgeEl.classList.add('hidden');
      }
    }

    const ratingEl = qs('[data-detail-rating]');
    ratingEl.innerHTML = `
      <span class="flex items-center gap-1">${renderStars(product.rating)}</span>
      <span class="text-slate-500">${product.rating.toFixed(1)} (${product.reviews} reviews)</span>
    `;

    const descriptionEl = qs('[data-detail-description]');
    if (descriptionEl) {
      descriptionEl.textContent = product.summary || product.description;
    }

    const unitSelect = qs('[data-detail-unit-select]');
    if (unitSelect) {
      unitSelect.innerHTML = product.units
        .map(
          (option) =>
            `<option value="${option.label}" data-price="${option.price}" data-old-price="${option.oldPrice || ''}">${option.label}</option>`
        )
        .join('');
    }

    const detailUnits = qs('[data-detail-units]');
    if (detailUnits) {
      detailUnits.innerHTML = buildDetailUnits(product);
    }

    const images =
      Array.isArray(product.images) && product.images.length
        ? product.images
        : [product.image];

    const mainImage = qs('[data-detail-main]');
    if (mainImage) {
      mainImage.src = images[0];
      mainImage.alt = product.name;
    }

    const thumbs = qs('[data-detail-thumbs]');
    if (thumbs) {
      thumbs.innerHTML = images
        .map(
          (src, index) => `
        <button class="group overflow-hidden rounded-2xl border ${index === 0 ? 'border-green-500 ring-2 ring-green-200' : 'border-green-100'} bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" data-thumb data-src="${src}" aria-label="View image ${index + 1}" aria-pressed="${index === 0 ? 'true' : 'false'}">
          <img src="${src}" alt="${product.name} thumbnail ${index + 1}" loading="lazy" decoding="async" class="h-20 w-full object-cover transition duration-300 group-hover:scale-105">
        </button>
      `
        )
        .join('');
      const firstThumb = qs('[data-thumb]', thumbs);
      if (firstThumb) {
        setActiveThumb(firstThumb);
      }
    }

    qs('[data-tab="description"]').textContent = product.description || product.summary;
    qs('[data-tab="nutrition"]').innerHTML = `
      <ul class="list-disc space-y-2 pl-5">
        ${product.nutrition.map((item) => `<li>${item}</li>`).join('')}
      </ul>
    `;
    qs('[data-tab="reviews"]').innerHTML = product.reviewList
      .map(
        (review) => `
        <div class="rounded-2xl border border-green-100 bg-white p-4 shadow-sm">
          <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-slate-900">${review.name}</p>
            <span class="flex items-center gap-1">${renderStars(review.rating)}</span>
          </div>
          <p class="mt-2 text-sm text-slate-600">${review.text}</p>
        </div>
      `
      )
      .join('');

    updateDetailPrice(detail);
    syncDetailState(detail);
  };

  const calculateTotals = (cart) => {
    const subtotal = cart.reduce((sum, item) => {
      const product = findProduct(item.id);
      if (!product) return sum;
      const unit = findUnit(product, item.unit);
      return sum + unit.price * item.qty;
    }, 0);
    const deliveryFee = subtotal >= 50 || subtotal === 0 ? 0 : 4.99;
    const total = subtotal + deliveryFee;
    return { subtotal, deliveryFee, total };
  };

  const updateSummary = (summaryRoot = document) => {
    const cart = getCart();
    const totals = calculateTotals(cart);
    qsa('[data-summary-subtotal]', summaryRoot).forEach((el) => {
      el.textContent = formatCurrency(totals.subtotal);
    });
    qsa('[data-summary-delivery]', summaryRoot).forEach((el) => {
      el.textContent = formatCurrency(totals.deliveryFee);
    });
    qsa('[data-summary-total]', summaryRoot).forEach((el) => {
      el.textContent = formatCurrency(totals.total);
    });
  };

  /* ─── Cart Offcanvas Drawer ────────────────────────────────────────── */
  let cartOffcanvasEl = null;

  const ensureCartOffcanvas = () => {
    if (cartOffcanvasEl) return cartOffcanvasEl;
    const existing = qs('[data-cart-offcanvas]');
    if (existing) { cartOffcanvasEl = existing; return existing; }

    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
      <div class="fixed inset-0 z-[110] hidden" data-cart-offcanvas aria-modal="true" role="dialog" aria-label="Shopping cart">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" data-cart-offcanvas-overlay></div>
        <div class="absolute inset-y-0 right-0 flex max-w-full">
          <div class="relative w-screen max-w-md transform transition-transform translate-x-full" data-cart-offcanvas-panel>
            <div class="flex h-full flex-col bg-white shadow-2xl">
              <div class="flex items-center justify-between border-b border-green-100 px-6 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Shopping Cart</h2>
                <button type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-green-100 bg-white text-slate-500 shadow-sm transition hover:text-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" data-cart-offcanvas-close aria-label="Close cart">
                  <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M6 6l12 12"></path>
                    <path d="M18 6l-12 12"></path>
                  </svg>
                </button>
              </div>
              <div class="flex-1 overflow-y-auto px-6 py-4" data-cart-offcanvas-items></div>
              <div class="border-t border-green-100 px-6 py-4" data-cart-offcanvas-footer>
                <div class="flex items-center justify-between text-sm text-slate-600">
                  <span>Subtotal</span>
                  <span class="font-semibold text-slate-900" data-cart-offcanvas-subtotal>$0.00</span>
                </div>
                <div class="mt-1 flex items-center justify-between text-sm text-slate-600">
                  <span>Delivery</span>
                  <span class="font-semibold text-slate-900" data-cart-offcanvas-delivery>$0.00</span>
                </div>
                <div class="mt-2 flex items-center justify-between border-t border-green-100 pt-2 text-base font-semibold text-slate-900">
                  <span>Total</span>
                  <span data-cart-offcanvas-total>$0.00</span>
                </div>
                <button type="button" class="mt-4 w-full rounded-2xl bg-green-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md" data-cart-offcanvas-checkout>Proceed to Checkout</button>
                <button type="button" class="mt-2 w-full text-center text-sm font-semibold text-green-700 transition hover:text-green-800" data-cart-offcanvas-close>Continue shopping</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
    document.body.append(wrapper.firstElementChild);
    cartOffcanvasEl = qs('[data-cart-offcanvas]');
    return cartOffcanvasEl;
  };

  const renderCartOffcanvas = () => {
    const offcanvas = cartOffcanvasEl || qs('[data-cart-offcanvas]');
    if (!offcanvas) return;
    const container = qs('[data-cart-offcanvas-items]', offcanvas);
    if (!container) return;
    const cart = getCart();

    if (!cart.length) {
      container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-16 text-center">
          <svg class="h-16 w-16 text-green-200" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.7 12.4a2 2 0 0 0 2 1.6h8.8a2 2 0 0 0 2-1.5l1.6-7.5H6"></path>
          </svg>
          <p class="mt-4 text-sm text-slate-500">Your cart is empty</p>
          <p class="mt-1 text-xs text-slate-400">Add some fresh picks to get started.</p>
        </div>
      `;
      const footer = qs('[data-cart-offcanvas-footer]', offcanvas);
      if (footer) footer.classList.add('hidden');
      return;
    }

    const footer = qs('[data-cart-offcanvas-footer]', offcanvas);
    if (footer) footer.classList.remove('hidden');

    container.innerHTML = cart
      .map((item) => {
        const product = findProduct(item.id);
        if (!product) return '';
        const unit = findUnit(product, item.unit);
        return `
          <div class="flex items-start gap-3 rounded-2xl border border-green-100 bg-white p-3 shadow-sm mb-3" data-cart-offcanvas-item data-product-id="${item.id}" data-unit="${item.unit}">
            <img src="${product.image}" alt="${product.name}" class="h-16 w-16 flex-shrink-0 rounded-xl object-cover">
            <div class="flex-1 min-w-0">
              <a href="${getProductDetailsUrl(product.id)}" class="text-sm font-semibold text-slate-900 hover:text-green-700 line-clamp-1">${product.name}</a>
              <p class="mt-0.5 text-xs text-slate-500">${item.unit}</p>
              <div class="mt-2 flex items-center justify-between">
                <div class="flex items-center gap-1.5 rounded-xl border border-green-100 bg-white px-2 py-1">
                  <button class="h-6 w-6 rounded-full bg-green-50 text-green-700 text-xs" type="button" aria-label="Decrease" data-action="dec">-</button>
                  <span class="min-w-[1.25rem] text-center text-xs font-semibold text-slate-700" data-qty>${item.qty}</span>
                  <button class="h-6 w-6 rounded-full bg-green-600 text-white text-xs" type="button" aria-label="Increase" data-action="inc">+</button>
                </div>
                <span class="text-sm font-semibold text-slate-900">${formatCurrency(unit.price * item.qty)}</span>
              </div>
            </div>
            <button class="flex-shrink-0 mt-0.5 text-slate-300 hover:text-rose-500 transition" type="button" data-action="remove" aria-label="Remove ${product.name}">
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M6 6l12 12"></path>
                <path d="M18 6l-12 12"></path>
              </svg>
            </button>
          </div>
        `;
      })
      .join('');

    // Update totals
    const totals = calculateTotals(cart);
    const subtotalEl = qs('[data-cart-offcanvas-subtotal]', offcanvas);
    const deliveryEl = qs('[data-cart-offcanvas-delivery]', offcanvas);
    const totalEl = qs('[data-cart-offcanvas-total]', offcanvas);
    if (subtotalEl) subtotalEl.textContent = formatCurrency(totals.subtotal);
    if (deliveryEl) deliveryEl.textContent = formatCurrency(totals.deliveryFee);
    if (totalEl) totalEl.textContent = formatCurrency(totals.total);
  };

  const openCartOffcanvas = () => {
    const offcanvas = ensureCartOffcanvas();
    renderCartOffcanvas();
    offcanvas.classList.remove('hidden');
    lockBodyScroll();
    requestAnimationFrame(() => {
      const panel = qs('[data-cart-offcanvas-panel]', offcanvas);
      if (panel) panel.classList.remove('translate-x-full');
    });
  };

  const closeCartOffcanvas = () => {
    const offcanvas = cartOffcanvasEl || qs('[data-cart-offcanvas]');
    if (!offcanvas) return;
    const panel = qs('[data-cart-offcanvas-panel]', offcanvas);
    if (panel) panel.classList.add('translate-x-full');
    setTimeout(() => {
      offcanvas.classList.add('hidden');
      unlockBodyScroll();
    }, 300);
  };

  const renderCart = () => {
    const container = qs('[data-cart-items]');
    if (!container) return;
    const cart = getCart();

    if (!cart.length) {
      container.innerHTML = `
        <div class="rounded-2xl border border-green-100 bg-white p-6 text-center text-sm text-slate-600 shadow-sm">
          Your cart is empty. Start adding fresh picks to your order.
          <div class="mt-4">
            <a href="products.html" class="rounded-2xl bg-green-600 px-5 py-2 text-sm font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">Browse products</a>
          </div>
        </div>
      `;
      updateSummary();
      return;
    }

    container.innerHTML = cart
      .map((item) => {
        const product = findProduct(item.id);
        if (!product) return '';
        const unit = findUnit(product, item.unit);
        return `
          <div class="flex flex-wrap items-center gap-4 rounded-2xl border border-green-100 bg-white p-4 shadow-sm" data-cart-item data-product-id="${item.id}" data-unit="${item.unit}">
            <img src="${product.image}" alt="${product.name}" class="h-20 w-20 rounded-xl object-cover">
            <div class="min-w-[180px] flex-1">
              <a href="${getProductDetailsUrl(product.id)}" class="text-sm font-semibold text-slate-900 hover:text-green-700">${product.name}</a>
              <p class="mt-1 text-xs text-slate-500">${item.unit}</p>
              <p class="mt-1 text-xs text-slate-500">${formatCurrency(unit.price)} per unit</p>
            </div>
            <div class="flex items-center gap-2 rounded-2xl border border-green-100 bg-white px-3 py-2">
              <button class="h-7 w-7 rounded-full bg-green-50 text-green-700" aria-label="Decrease quantity" data-action="dec">-</button>
              <span class="min-w-[1.5rem] text-center text-xs font-semibold text-slate-700" data-qty>${item.qty}</span>
              <button class="h-7 w-7 rounded-full bg-green-600 text-white" aria-label="Increase quantity" data-action="inc">+</button>
            </div>
            <div class="min-w-[80px] text-right text-sm font-semibold text-slate-900">${formatCurrency(unit.price * item.qty)}</div>
            <button class="text-xs font-semibold text-slate-400 hover:text-rose-500" data-action="remove">Remove</button>
          </div>
        `;
      })
      .join('');

    updateSummary();
  };

  const renderCheckoutSummary = () => {
    const container = qs('[data-summary-items]');
    if (!container) return;
    const cart = getCart();
    if (!cart.length) {
      container.innerHTML = '<p class="text-sm text-slate-500">No items added yet.</p>';
      updateSummary();
      return;
    }

    container.innerHTML = cart
      .map((item) => {
        const product = findProduct(item.id);
        if (!product) return '';
        const unit = findUnit(product, item.unit);
        return `
          <div class="flex items-center justify-between text-sm text-slate-600">
            <span>${product.name} (${item.unit}) x${item.qty}</span>
            <span class="font-semibold text-slate-900">${formatCurrency(unit.price * item.qty)}</span>
          </div>
        `;
      })
      .join('');

    updateSummary();
  };

  document.addEventListener('change', (event) => {
    const modalUnit = event.target.closest('[data-modal-unit]');
    if (modalUnit) {
      const modal = modalUnit.closest('[data-modal]');
      updateModalPrice(modal, modalUnit);
      return;
    }

    const detailUnitRadio = event.target.closest('[data-detail-unit]');
    if (detailUnitRadio) {
      const detail = detailUnitRadio.closest('[data-product-detail]');
      if (detail) {
        const select = qs('[data-detail-unit-select]', detail);
        if (select) {
          select.value = detailUnitRadio.value;
        }
        updateDetailPrice(detail);
        syncDetailState(detail);
      }
      return;
    }

    const detailUnit = event.target.closest('[data-detail-unit-select]');
    if (detailUnit) {
      const detail = detailUnit.closest('[data-product-detail]');
      updateDetailPrice(detail);
      syncDetailState(detail);
    }
  });

  document.addEventListener('click', (event) => {
    /* ── Cart Offcanvas triggers ─────────────────────────────────────── */
    const openCartBtn = event.target.closest('[data-action="open-cart-offcanvas"]');
    if (openCartBtn) {
      event.preventDefault();
      openCartOffcanvas();
      return;
    }

    const cartOffcanvasClose = event.target.closest('[data-cart-offcanvas-close]');
    if (cartOffcanvasClose) {
      closeCartOffcanvas();
      return;
    }

    const cartOffcanvasOverlay = event.target.closest('[data-cart-offcanvas-overlay]');
    if (cartOffcanvasOverlay) {
      closeCartOffcanvas();
      return;
    }

    /* ── Cart offcanvas item actions (inc / dec / remove) ─────────── */
    const offcanvasItem = event.target.closest('[data-cart-offcanvas-item]');
    if (offcanvasItem) {
      const incBtn = event.target.closest('[data-action="inc"]');
      const decBtn = event.target.closest('[data-action="dec"]');
      const removeBtn = event.target.closest('[data-action="remove"]');
      const pid = offcanvasItem.dataset.productId;
      const punit = offcanvasItem.dataset.unit;

      if (incBtn) {
        updateCartItem(pid, punit, 1);
        renderCartOffcanvas();
        renderCart();
        renderCheckoutSummary();
        updateCartBadges();
        return;
      }
      if (decBtn) {
        updateCartItem(pid, punit, -1);
        renderCartOffcanvas();
        renderCart();
        renderCheckoutSummary();
        updateCartBadges();
        return;
      }
      if (removeBtn) {
        removeCartItem(pid, punit);
        renderCartOffcanvas();
        renderCart();
        renderCheckoutSummary();
        updateCartBadges();
        return;
      }
      // Allow link clicks inside the item to pass through
      return;
    }

    const modalClose = event.target.closest('[data-modal-close]');
    const modalOverlay = event.target.closest('[data-modal-overlay]');
    if (modalClose || modalOverlay) {
      closeProductModal();
      return;
    }
    const modalRoot = event.target.closest('[data-modal="product"]');
    if (modalRoot && !event.target.closest('[data-modal-card]')) {
      closeProductModal();
      return;
    }

    const quickViewButton = event.target.closest('[data-action="quick-view"]');
    if (quickViewButton) {
      const card = quickViewButton.closest('[data-featured-card]');
      if (card) {
        const id = String(card.dataset.productId || quickViewButton.dataset.productId || '');
        if (id) {
          openProductModal(id, { mode: 'quick' });
        }
      }
      return;
    }

    const modalAction = event.target.closest('[data-modal-action]');
    if (modalAction) {
      const modal = modalAction.closest('[data-modal]');
      const direction = modalAction.dataset.modalAction;
      updateModalQty(modal, direction === 'inc' ? 1 : -1);
      return;
    }

    const modalThumb = event.target.closest('[data-modal-thumb]');
    if (modalThumb) {
      const modal = modalThumb.closest('[data-modal="product"]');
      if (modal) {
        const src = modalThumb.dataset.src;
        const imageEl = qs('[data-modal-image]', modal);
        if (imageEl && src) {
          imageEl.src = src;
        }
        qsa('[data-modal-thumb]', modal).forEach((button) => {
          button.classList.remove('border-green-500', 'ring-2', 'ring-green-200');
          button.classList.add('border-green-100');
          button.setAttribute('aria-pressed', 'false');
        });
        modalThumb.classList.add('border-green-500', 'ring-2', 'ring-green-200');
        modalThumb.classList.remove('border-green-100');
        modalThumb.setAttribute('aria-pressed', 'true');
      }
      return;
    }

    const addButton = event.target.closest('[data-action="add"]');
    if (addButton) {
      const modal = addButton.closest('[data-modal]');
      const detail = addButton.closest('[data-product-detail]');

      if (modal) {
        const productId = modal.dataset.productId || modalState.activeProductId;
        const unitInput = qs('[data-modal-unit]:checked', modal) || qs('[data-modal-unit]', modal);
        const qtyEl = qs('[data-modal-qty]', modal);
        const unit = unitInput ? unitInput.value : null;
        const qty = qtyEl ? Number(qtyEl.textContent || 1) : 1;

        if (productId && unit) {
          addToCart(productId, unit, qty);
        }

        closeProductModal();
        updateCartBadges();
        renderCartOffcanvas();
        openCartOffcanvas();
        return;
      }

      if (detail) {
        const id = detail.dataset.productId;
        const unit = qs('[data-detail-unit-select]', detail).value;
        addToCart(id, unit);
        syncDetailState(detail);
      }

      updateCartBadges();
      return;
    }

    const incButton = event.target.closest('[data-action="inc"]');
    if (incButton) {
      const cartItem = incButton.closest('[data-cart-item]');
      const detail = incButton.closest('[data-product-detail]');

      if (cartItem) {
        updateCartItem(cartItem.dataset.productId, cartItem.dataset.unit, 1);
        renderCart();
        renderCheckoutSummary();
      } else if (detail) {
        const id = detail.dataset.productId;
        const unit = qs('[data-detail-unit-select]', detail).value;
        updateCartItem(id, unit, 1);
        syncDetailState(detail);
      }

      updateCartBadges();
      return;
    }

    const decButton = event.target.closest('[data-action="dec"]');
    if (decButton) {
      const cartItem = decButton.closest('[data-cart-item]');
      const detail = decButton.closest('[data-product-detail]');

      if (cartItem) {
        updateCartItem(cartItem.dataset.productId, cartItem.dataset.unit, -1);
        renderCart();
        renderCheckoutSummary();
      } else if (detail) {
        const id = detail.dataset.productId;
        const unit = qs('[data-detail-unit-select]', detail).value;
        updateCartItem(id, unit, -1);
        syncDetailState(detail);
      }

      updateCartBadges();
      return;
    }

    const removeButton = event.target.closest('[data-action="remove"]');
    if (removeButton) {
      const cartItem = removeButton.closest('[data-cart-item]');
      if (cartItem) {
        removeCartItem(cartItem.dataset.productId, cartItem.dataset.unit);
        renderCart();
        renderCheckoutSummary();
        updateCartBadges();
      }
      return;
    }

    const thumb = event.target.closest('[data-thumb]');
    if (thumb) {
      const src = thumb.dataset.src;
      const mainImage = qs('[data-detail-main]');
      if (mainImage && src) {
        mainImage.src = src;
      }
      setActiveThumb(thumb);
    }

    const tabButton = event.target.closest('[data-tab-target]');
    if (tabButton) {
      const container = tabButton.closest('[data-tabs]');
      const target = tabButton.dataset.tabTarget;
      qsa('[data-tab-target]', container).forEach((btn) => {
        btn.classList.remove('border-b-2', 'border-green-600', 'text-green-700');
        btn.classList.add('text-slate-500');
      });
      tabButton.classList.add('border-b-2', 'border-green-600', 'text-green-700');
      tabButton.classList.remove('text-slate-500');

      qsa('[data-tab]', container).forEach((panel) => {
        panel.classList.toggle('hidden', panel.dataset.tab !== target);
      });
    }
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') return;
    // Close cart offcanvas first if open
    const cartOff = cartOffcanvasEl || qs('[data-cart-offcanvas]');
    if (cartOff && !cartOff.classList.contains('hidden')) {
      closeCartOffcanvas();
      return;
    }
    const modal = modalState.element || qs('[data-modal="product"]');
    if (!modal || modal.classList.contains('hidden')) return;
    closeProductModal();
  });

  initProductsFromDOM();
  ensureProductModal();
  ensureCartOffcanvas();
  initMagnify();
  updateNavActive();
  initStickyHeader();
  initHeroSlider();
  initCountdowns();
  initDealsCarousel();
  renderProductDetails();
  renderCart();
  renderCheckoutSummary();
  updateCartBadges();
  initScrollReveal();
})();
