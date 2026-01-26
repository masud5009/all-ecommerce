(() => {



  const slider = document.querySelector("[data-hero-slider]");
  if (!slider) return;

  const slides = Array.from(slider.querySelectorAll("[data-slide]"));
  const dots = Array.from(slider.querySelectorAll("[data-dot]"));
  const prev = slider.querySelector("[data-prev]");
  const next = slider.querySelector("[data-next]");
  let index = Math.max(0, slides.findIndex((s) => !s.classList.contains("hidden")));

  const show = (nextIndex) => {
    index = (nextIndex + slides.length) % slides.length;
    slides.forEach((slide, idx) => {
      const isActive = idx === index;
      slide.classList.toggle("hidden", !isActive);
      slide.setAttribute("aria-hidden", isActive ? "false" : "true");
    });
    dots.forEach((dot, idx) => {
      const isActive = idx === index;
      dot.classList.toggle("bg-green-600", isActive);
      dot.classList.toggle("bg-green-200", !isActive);
      dot.setAttribute("aria-current", isActive ? "true" : "false");
    });
  };

  prev?.addEventListener("click", () => show(index - 1));
  next?.addEventListener("click", () => show(index + 1));
  dots.forEach((dot, idx) => dot.addEventListener("click", () => show(idx)));

  slider.addEventListener("keydown", (event) => {
    const tag = event.target.tagName.toLowerCase();
    if (["input", "textarea", "select", "button", "a"].includes(tag)) return;
    if (event.key === "ArrowLeft") {
      event.preventDefault();
      show(index - 1);
    }
    if (event.key === "ArrowRight") {
      event.preventDefault();
      show(index + 1);
    }
  });

  show(index);




  const products = [
    {
      id: 'avocado',
      name: 'Organic Hass Avocado',
      category: 'Fresh Produce',
      rating: 4.7,
      reviews: 142,
      badge: 'Farm fresh',
      image: 'https://picsum.photos/seed/avocado/600/400',
      isDeal: true,
      popular: true,
      description: 'Buttery and rich avocados sourced from small farms for creamy texture and clean flavor.',
      nutrition: ['Calories 160', 'Fat 14g', 'Fiber 7g', 'Potassium 485mg'],
      reviewList: [
        { name: 'Ariana', rating: 5, text: 'Perfect ripeness and packed carefully.' },
        { name: 'Chris', rating: 4, text: 'Great taste and value for the size.' }
      ],
      units: [
        { label: '1kg', price: 6.5, oldPrice: 7.4 },
        { label: '500g', price: 3.5, oldPrice: 4.1 },
        { label: '250g', price: 2.0, oldPrice: 2.5 }
      ]
    },
    {
      id: 'strawberries',
      name: 'Sweet Strawberries',
      category: 'Fresh Produce',
      rating: 4.6,
      reviews: 118,
      badge: 'Seasonal',
      image: 'https://picsum.photos/seed/strawberries/600/400',
      isDeal: true,
      popular: true,
      description: 'Juicy, fragrant strawberries picked at peak sweetness for snacking and desserts.',
      nutrition: ['Calories 33', 'Vitamin C 59mg', 'Fiber 2g', 'Natural sugars 4.9g'],
      reviewList: [
        { name: 'Maya', rating: 5, text: 'Sweet and vibrant, made great smoothies.' },
        { name: 'Leo', rating: 4, text: 'Fresh and firm, held up well in transit.' }
      ],
      units: [
        { label: '1kg', price: 8.2, oldPrice: 9.0 },
        { label: '500g', price: 4.4, oldPrice: 4.9 },
        { label: '250g', price: 2.5, oldPrice: 2.9 }
      ]
    },
    {
      id: 'spinach',
      name: 'Baby Spinach Leaves',
      category: 'Fresh Produce',
      rating: 4.5,
      reviews: 96,
      badge: 'Washed',
      image: 'https://picsum.photos/seed/spinach/600/400',
      isDeal: true,
      popular: true,
      description: 'Tender baby spinach pre-washed and ready for salads, sautes, or smoothies.',
      nutrition: ['Calories 23', 'Protein 2.9g', 'Iron 2.7mg', 'Vitamin A 469mcg'],
      reviewList: [
        { name: 'Jordan', rating: 5, text: 'Crisp and clean, no wilted leaves.' },
        { name: 'Nia', rating: 4, text: 'Great for quick weeknight meals.' }
      ],
      units: [
        { label: '1kg', price: 4.5, oldPrice: 5.2 },
        { label: '500g', price: 2.6, oldPrice: 3.1 },
        { label: '250g', price: 1.5, oldPrice: 1.8 }
      ]
    },
    {
      id: 'roma-tomatoes',
      name: 'Roma Tomatoes',
      category: 'Fresh Produce',
      rating: 4.4,
      reviews: 83,
      badge: 'Vine ripened',
      image: 'https://picsum.photos/seed/tomatoes/600/400',
      isDeal: true,
      popular: true,
      description: 'Rich, meaty Roma tomatoes ideal for sauces, salads, and roasting.',
      nutrition: ['Calories 18', 'Vitamin C 14mg', 'Potassium 237mg', 'Fiber 1.2g'],
      reviewList: [
        { name: 'Elle', rating: 5, text: 'Great for pasta sauce and salsa.' },
        { name: 'Sam', rating: 4, text: 'Firm and flavorful, no bruising.' }
      ],
      units: [
        { label: '1kg', price: 3.8, oldPrice: 4.4 },
        { label: '500g', price: 2.1, oldPrice: 2.6 },
        { label: '250g', price: 1.3, oldPrice: 1.6 }
      ]
    },
    {
      id: 'grapes',
      name: 'Red Seedless Grapes',
      category: 'Fresh Produce',
      rating: 4.5,
      reviews: 102,
      badge: 'Sweet bite',
      image: 'https://picsum.photos/seed/grapes/600/400',
      isDeal: true,
      popular: false,
      description: 'Crunchy, seedless grapes with a balanced sweet flavor and crisp texture.',
      nutrition: ['Calories 69', 'Vitamin K 14mcg', 'Carbs 18g', 'Water 81%'],
      reviewList: [
        { name: 'Priya', rating: 5, text: 'Loved the crunch and freshness.' },
        { name: 'Omar', rating: 4, text: 'Great for lunchboxes and snacking.' }
      ],
      units: [
        { label: '1kg', price: 6.9, oldPrice: 7.6 },
        { label: '500g', price: 3.8, oldPrice: 4.2 },
        { label: '250g', price: 2.2, oldPrice: 2.6 }
      ]
    },
    {
      id: 'salmon',
      name: 'Atlantic Salmon Fillet',
      category: 'Seafood',
      rating: 4.8,
      reviews: 154,
      badge: 'Chef pick',
      image: 'https://picsum.photos/seed/salmon/600/400',
      isDeal: true,
      popular: true,
      description: 'Rich, buttery salmon fillets trimmed and portioned for easy weeknight dinners.',
      nutrition: ['Calories 208', 'Protein 20g', 'Omega-3 2.3g', 'Vitamin D 10mcg'],
      reviewList: [
        { name: 'Grace', rating: 5, text: 'Perfect thickness and flavor.' },
        { name: 'Ben', rating: 5, text: 'Arrived chilled and beautifully cut.' }
      ],
      units: [
        { label: '1kg', price: 18.5, oldPrice: 21.0 },
        { label: '500g', price: 9.8, oldPrice: 11.2 },
        { label: '250g', price: 5.4, oldPrice: 6.2 }
      ]
    },
    {
      id: 'chicken',
      name: 'Chicken Breast Fillet',
      category: 'Meat & Poultry',
      rating: 4.6,
      reviews: 131,
      badge: 'Trimmed',
      image: 'https://picsum.photos/seed/chicken/600/400',
      isDeal: true,
      popular: true,
      description: 'Lean, tender chicken breast fillets ready for grilling, roasting, or stir-fry.',
      nutrition: ['Calories 165', 'Protein 31g', 'Fat 3.6g', 'Sodium 74mg'],
      reviewList: [
        { name: 'Ivy', rating: 5, text: 'Moist and clean cuts every time.' },
        { name: 'Jon', rating: 4, text: 'Great for meal prep and salads.' }
      ],
      units: [
        { label: '1kg', price: 10.2, oldPrice: 11.4 },
        { label: '500g', price: 5.5, oldPrice: 6.2 },
        { label: '250g', price: 3.1, oldPrice: 3.6 }
      ]
    },
    {
      id: 'sirloin',
      name: 'Beef Sirloin Steak',
      category: 'Meat & Poultry',
      rating: 4.7,
      reviews: 97,
      badge: 'Grass fed',
      image: 'https://picsum.photos/seed/sirloin/600/400',
      isDeal: true,
      popular: false,
      description: 'Juicy sirloin with a rich bite, hand-trimmed for tenderness.',
      nutrition: ['Calories 217', 'Protein 26g', 'Fat 11g', 'Iron 2.3mg'],
      reviewList: [
        { name: 'Lena', rating: 5, text: 'Loved the marbling and flavor.' },
        { name: 'Marcus', rating: 4, text: 'Great grill results and easy to cook.' }
      ],
      units: [
        { label: '1kg', price: 16.8, oldPrice: 19.0 },
        { label: '500g', price: 8.9, oldPrice: 10.2 },
        { label: '250g', price: 4.9, oldPrice: 5.6 }
      ]
    },
    {
      id: 'shrimp',
      name: 'Wild Gulf Shrimp',
      category: 'Seafood',
      rating: 4.5,
      reviews: 88,
      badge: 'Peeled',
      image: 'https://picsum.photos/seed/shrimp/600/400',
      isDeal: true,
      popular: false,
      description: 'Sweet, wild-caught shrimp peeled and deveined for quick cooking.',
      nutrition: ['Calories 99', 'Protein 24g', 'Cholesterol 189mg', 'Sodium 111mg'],
      reviewList: [
        { name: 'Tessa', rating: 5, text: 'Clean flavor and great texture.' },
        { name: 'Ravi', rating: 4, text: 'Perfect for garlic butter shrimp.' }
      ],
      units: [
        { label: '1kg', price: 14.6, oldPrice: 16.8 },
        { label: '500g', price: 7.8, oldPrice: 9.1 },
        { label: '250g', price: 4.3, oldPrice: 5.0 }
      ]
    },
    {
      id: 'almonds',
      name: 'Raw Almonds',
      category: 'Pantry',
      rating: 4.4,
      reviews: 75,
      badge: 'New crop',
      image: 'https://picsum.photos/seed/almonds/600/400',
      isDeal: false,
      popular: true,
      description: 'Crunchy raw almonds packed with protein and healthy fats.',
      nutrition: ['Calories 579', 'Protein 21g', 'Fat 50g', 'Fiber 12g'],
      reviewList: [
        { name: 'Drew', rating: 5, text: 'Fresh and crunchy, great snack.' },
        { name: 'Salma', rating: 4, text: 'Excellent for baking and granola.' }
      ],
      units: [
        { label: '1kg', price: 12.4, oldPrice: 14.0 },
        { label: '500g', price: 6.6, oldPrice: 7.5 },
        { label: '250g', price: 3.6, oldPrice: 4.2 }
      ]
    },
    {
      id: 'cheddar',
      name: 'Aged Cheddar Block',
      category: 'Dairy',
      rating: 4.6,
      reviews: 69,
      badge: 'Aged 12 months',
      image: 'https://picsum.photos/seed/cheddar/600/400',
      isDeal: false,
      popular: false,
      description: 'Sharp, creamy cheddar with a rich finish for cheese boards and sandwiches.',
      nutrition: ['Calories 403', 'Protein 25g', 'Calcium 721mg', 'Fat 33g'],
      reviewList: [
        { name: 'Sofia', rating: 5, text: 'Bold flavor and smooth texture.' },
        { name: 'Ethan', rating: 4, text: 'Great for grilled cheese nights.' }
      ],
      units: [
        { label: '1kg', price: 9.6, oldPrice: 10.8 },
        { label: '500g', price: 5.0, oldPrice: 5.8 },
        { label: '250g', price: 2.9, oldPrice: 3.4 }
      ]
    },
    {
      id: 'jasmine-rice',
      name: 'Jasmine Rice',
      category: 'Pantry',
      rating: 4.3,
      reviews: 58,
      badge: 'Aromatic',
      image: 'https://picsum.photos/seed/rice/600/400',
      isDeal: true,
      popular: false,
      description: 'Fragrant jasmine rice with soft, fluffy grains for daily meals.',
      nutrition: ['Calories 365', 'Protein 7g', 'Carbs 80g', 'Iron 1.5mg'],
      reviewList: [
        { name: 'Nora', rating: 4, text: 'Cooks evenly and smells amazing.' },
        { name: 'Pete', rating: 4, text: 'Good staple for the pantry.' }
      ],
      units: [
        { label: '1kg', price: 4.2, oldPrice: 4.9 },
        { label: '500g', price: 2.4, oldPrice: 2.9 },
        { label: '250g', price: 1.4, oldPrice: 1.7 }
      ]
    }
  ];

  const storeKey = 'grocery_cart';
  const wishlistKey = 'grocery_wishlist';
  const modalState = {
    element: null,
    activeProductId: null,
    lastFocused: null
  };
  let lastCartCount = null;

  const qs = (selector, scope = document) => scope.querySelector(selector);
  const qsa = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));

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

  const getWishlist = () => {
    try {
      return JSON.parse(localStorage.getItem(wishlistKey)) || [];
    } catch (error) {
      return [];
    }
  };

  const saveWishlist = (wishlist) => {
    localStorage.setItem(wishlistKey, JSON.stringify(wishlist));
  };

  const toggleWishlist = (id) => {
    const wishlist = getWishlist();
    const index = wishlist.indexOf(id);
    if (index >= 0) {
      wishlist.splice(index, 1);
    } else {
      wishlist.push(id);
    }
    saveWishlist(wishlist);
    return wishlist;
  };

  const findProduct = (id) => products.find((product) => product.id === id);
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

  const computeDiscount = (price, oldPrice) => {
    if (!oldPrice || oldPrice <= price) return 0;
    return Math.round((1 - price / oldPrice) * 100);
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

  const updateUnitDisplay = (card) => {
    const select = qs('[data-unit-select]', card);
    if (!select) return;
    const selected = select.selectedOptions[0];
    if (!selected) return;
    const price = Number(selected.dataset.price || 0);
    const oldPrice = Number(selected.dataset.oldPrice || 0);
    const priceEl = qs('[data-price]', card);
    const oldEl = qs('[data-old-price]', card);
    const badgeEl = qs('[data-discount-badge]', card);
    priceEl.textContent = formatCurrency(price);
    if (oldPrice && oldPrice > price) {
      const discount = computeDiscount(price, oldPrice);
      oldEl.textContent = formatCurrency(oldPrice);
      oldEl.classList.remove('hidden');
      if (badgeEl) {
        badgeEl.textContent = `-${discount}%`;
        badgeEl.classList.remove('hidden');
      }
    } else {
      oldEl.classList.add('hidden');
      if (badgeEl) {
        if (card.dataset.deal === 'true') {
          badgeEl.textContent = 'Deal';
          badgeEl.classList.remove('hidden');
        } else {
          badgeEl.classList.add('hidden');
        }
      }
    }
  };

  const syncCardState = (card) => {
    const id = card.dataset.productId;
    const unit = qs('[data-unit-select]', card).value;
    const cart = getCart();
    const item = getCartItem(cart, id, unit);
    const addBtn = qs('[data-action="add"]', card);
    const stepper = qs('[data-qty-stepper]', card);
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

  const syncWishlistState = (card, wishlist) => {
    const button = qs('[data-action="wishlist"]', card);
    if (!button) return;
    const id = card.dataset.productId;
    const name = card.dataset.productName || 'item';
    const isActive = wishlist.includes(id);
    button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
    button.setAttribute(
      'aria-label',
      `${isActive ? 'Remove' : 'Add'} ${name} ${isActive ? 'from' : 'to'} wishlist`
    );
  };

  const refreshAllProductCards = () => {
    const wishlist = getWishlist();
    qsa('[data-product-card]').forEach((card) => {
      updateUnitDisplay(card);
      syncCardState(card);
      syncWishlistState(card, wishlist);
    });
  };

  const productCardTemplate = (product, layout = 'grid') => {
    const unit = product.units[0];
    const discount = computeDiscount(unit.price, unit.oldPrice);
    const badgeLabel = discount ? `-${discount}%` : product.isDeal ? 'Deal' : '';
    const wrapperClass = layout === 'slider' ? 'min-w-[220px] snap-start sm:min-w-[240px] lg:min-w-[260px]' : '';
    const hasVariants = product.units.length > 1;
    const selectClass = hasVariants
      ? 'hidden'
      : 'w-full rounded-full border border-green-100 bg-white px-3 py-2 text-xs text-slate-600 focus:border-green-300 focus:ring-2 focus:ring-green-100';
    const selectAria = hasVariants ? 'aria-hidden="true" tabindex="-1"' : '';
    const actionLabel = hasVariants ? 'Select options' : 'Quick add';
    const variantNote = hasVariants
      ? `
        <div class="flex items-center gap-2 text-xs text-slate-500">
          <span class="rounded-full bg-green-50 px-2 py-1 text-[10px] font-semibold text-green-700">Variants</span>
          <span>${product.units.length} sizes available</span>
        </div>
      `
      : '';

    const unitOptions = product.units
      .map(
        (option) =>
          `<option value="${option.label}" data-price="${option.price}" data-old-price="${option.oldPrice || ''}">${option.label}</option>`
      )
      .join('');

    const stockNote = product.isDeal
      ? '<span class="rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-semibold text-green-700">Only 5 left</span>'
      : '';
    const badgePill = product.badge
      ? `<span class="rounded-full bg-green-50 px-2 py-0.5 text-[10px] font-semibold text-green-700">${product.badge}</span>`
      : '';

    return `
      <div class="${wrapperClass}">
        <div class="group relative flex h-full flex-col rounded-2xl border border-green-100 bg-white p-4 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-green-200 hover:shadow-[0_20px_45px_rgba(15,23,42,0.12)]" data-product-card data-product-id="${product.id}" data-product-name="${product.name}" data-deal="${product.isDeal ? 'true' : 'false'}" data-reveal-child>
          <span class="absolute left-4 top-4 rounded-full bg-green-600 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-white shadow-sm ${badgeLabel ? '' : 'hidden'}" data-discount-badge>${badgeLabel}</span>
          <button class="wishlist-btn absolute right-4 top-4 inline-flex h-9 w-9 items-center justify-center rounded-full border border-green-100 bg-white/90 text-slate-500 shadow-sm opacity-0 transition duration-300 group-hover:opacity-100 group-focus-within:opacity-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" type="button" data-action="wishlist" aria-label="Add ${product.name} to wishlist" aria-pressed="false">
            <svg class="wishlist-icon h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0l-.9.9-.9-.9a5.5 5.5 0 0 0-7.8 7.8l8.7 8.7 8.7-8.7a5.5 5.5 0 0 0 0-7.8Z"></path>
            </svg>
          </button>
          <button class="absolute right-4 top-14 inline-flex items-center gap-1 rounded-full border border-green-100 bg-white/95 px-3 py-1 text-[11px] font-semibold text-slate-600 shadow-sm opacity-0 transition duration-300 group-hover:opacity-100 group-focus-within:opacity-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" type="button" data-action="quick-view" aria-label="Quick view ${product.name}">
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path d="M2 12s4-6 10-6 10 6 10 6-4 6-10 6-10-6-10-6Z"></path>
              <circle cx="12" cy="12" r="3"></circle>
            </svg>
            Quick view
          </button>
          <div class="relative overflow-hidden rounded-2xl bg-green-50">
            <a href="product-details.html?id=${product.id}" class="block">
              <img src="${product.image}" alt="${product.name}" class="h-40 w-full object-cover transition duration-500 group-hover:scale-105">
            </a>
            <button class="absolute bottom-3 right-3 inline-flex h-10 w-10 items-center justify-center rounded-full bg-green-600 text-white shadow-lg opacity-0 translate-y-2 transition duration-300 group-hover:opacity-100 group-hover:translate-y-0 group-focus-within:opacity-100 group-focus-within:translate-y-0 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" data-action="add" aria-label="${actionLabel} ${product.name}">
              <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M12 5v14"></path>
                <path d="M5 12h14"></path>
              </svg>
            </button>
          </div>
          <div class="mt-4 space-y-2">
            <div class="flex items-start justify-between gap-2">
              <a href="product-details.html?id=${product.id}" class="text-sm font-semibold text-slate-900 transition hover:text-green-700">${product.name}</a>
              ${badgePill}
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
              <span class="flex items-center gap-1">${renderStars(product.rating)}</span>
              <span>${product.rating.toFixed(1)} (${product.reviews} reviews)</span>
              ${stockNote}
            </div>
            ${variantNote}
            <select class="${selectClass}" data-unit-select aria-label="Select unit" ${selectAria}>
              ${unitOptions}
            </select>
            <div class="flex items-end justify-between gap-3">
              <div>
                <p class="text-lg font-semibold text-slate-900" data-price>${formatCurrency(unit.price)}</p>
                <p class="text-xs text-slate-400 line-through ${unit.oldPrice ? '' : 'hidden'}" data-old-price>${unit.oldPrice ? formatCurrency(unit.oldPrice) : ''}</p>
              </div>
              <div class="hidden items-center gap-2 rounded-full border border-green-100 bg-white px-3 py-2 shadow-sm" data-qty-stepper>
                <button class="h-7 w-7 rounded-full bg-green-50 text-green-700 transition hover:bg-green-100 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" aria-label="Decrease quantity" data-action="dec">-</button>
                <span class="min-w-[1.5rem] text-center text-xs font-semibold text-slate-700" data-qty>1</span>
                <button class="h-7 w-7 rounded-full bg-green-600 text-white transition hover:bg-green-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-green-200" aria-label="Increase quantity" data-action="inc">+</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    `;
  };

  const setCardUnitSelection = (productId, unitLabel) => {
    if (!productId || !unitLabel) return;
    qsa(`[data-product-card][data-product-id="${productId}"]`).forEach((card) => {
      const select = qs('[data-unit-select]', card);
      if (!select) return;
      select.value = unitLabel;
      updateUnitDisplay(card);
      syncCardState(card);
    });
  };

  const buildModalUnits = (product, selectedUnit) =>
    product.units
      .map((option, index) => {
        const isChecked = selectedUnit ? option.label === selectedUnit : index === 0;
        const discount = computeDiscount(option.price, option.oldPrice);
        const badge = discount
          ? `<span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-700">-${discount}%</span>`
          : '<span class="rounded-full border border-green-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500">Standard</span>';
        return `
          <label class="group relative flex cursor-pointer items-center gap-3">
            <input class="peer sr-only" type="radio" name="modal-unit" value="${option.label}" data-modal-unit data-price="${option.price}" data-old-price="${option.oldPrice || ''}" ${isChecked ? 'checked' : ''}>
            <div class="flex w-full items-center justify-between rounded-2xl border border-green-100 bg-white px-5 py-4 text-sm shadow-sm transition hover:border-green-300 hover:shadow-md peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:ring-2 peer-checked:ring-green-200">
              <div>
                <p class="font-semibold text-slate-900">${option.label}</p>
                <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                  ${badge}
                  <span>${discount ? 'Limited deal' : 'Standard price'}</span>
                </div>
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
        const discount = computeDiscount(option.price, option.oldPrice);
        const badge = discount
          ? `<span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-700">-${discount}%</span>`
          : '<span class="rounded-full border border-green-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500">Standard</span>';
        return `
          <label class="group relative flex cursor-pointer items-center gap-3">
            <input class="peer sr-only" type="radio" name="detail-unit" value="${option.label}" data-detail-unit data-price="${option.price}" data-old-price="${option.oldPrice || ''}" ${isChecked ? 'checked' : ''}>
            <div class="flex w-full items-center justify-between rounded-2xl border border-green-100 bg-white px-5 py-4 text-sm shadow-sm transition hover:border-green-300 hover:shadow-md peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:ring-2 peer-checked:ring-green-200">
              <div>
                <p class="font-semibold text-slate-900">${option.label}</p>
                <div class="mt-1 flex items-center gap-2 text-xs text-slate-500">
                  ${badge}
                  <span>${discount ? 'Limited deal' : 'Standard price'}</span>
                </div>
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
                <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500">
                  <span class="rounded-full bg-green-100 px-3 py-1 text-[10px] font-semibold uppercase tracking-wide text-green-700" data-modal-category>Category</span>
                  <span class="rounded-full border border-green-100 bg-white px-3 py-1 text-[10px] font-semibold text-slate-600" data-modal-badge>Badge</span>
                </div>
              </div>
              <div class="p-6 sm:p-8">
                <p class="text-[11px] font-semibold uppercase tracking-wide text-green-600" data-modal-kicker>Quick view</p>
                <h3 class="mt-2 text-2xl font-semibold text-slate-900" id="product-modal-title" data-modal-name>Product name</h3>
                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-slate-500" data-modal-rating></div>
                <p class="mt-4 text-sm text-slate-600" data-modal-description></p>
                <div class="mt-5">
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
                <div class="mt-5 flex items-center justify-between text-xs text-slate-500">
                  <span>Delivery in 90 min</span>
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

    qs('[data-modal-kicker]', modal).textContent = options.mode === 'options' ? 'Select options' : 'Quick view';
    qs('[data-modal-name]', modal).textContent = product.name;
    qs('[data-modal-category]', modal).textContent = product.category;

    const badgeEl = qs('[data-modal-badge]', modal);
    if (product.badge) {
      badgeEl.textContent = product.badge;
      badgeEl.classList.remove('hidden');
    } else {
      badgeEl.classList.add('hidden');
    }

    const ratingEl = qs('[data-modal-rating]', modal);
    ratingEl.innerHTML = `
      <span class="flex items-center gap-1">${renderStars(product.rating)}</span>
      <span>${product.rating.toFixed(1)} (${product.reviews} reviews)</span>
    `;

    qs('[data-modal-description]', modal).textContent = product.description;

    const imageEl = qs('[data-modal-image]', modal);
    imageEl.src = product.image;
    imageEl.alt = product.name;

    const unitsEl = qs('[data-modal-units]', modal);
    unitsEl.innerHTML = buildModalUnits(product, selectedUnit);
    const selectedInput = qs('[data-modal-unit]:checked', modal) || qs('[data-modal-unit]', modal);
    updateModalPrice(modal, selectedInput);

    const qtyEl = qs('[data-modal-qty]', modal);
    if (qtyEl) qtyEl.textContent = '1';

    const linkEl = qs('[data-modal-link]', modal);
    linkEl.href = `product-details.html?id=${product.id}`;

    modal.classList.remove('hidden');
    modal.setAttribute('aria-hidden', 'false');
    modal.scrollTop = 0;
    lockBodyScroll();

    if (options.focusUnits) {
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

  const renderProductCollections = () => {
    const grids = qsa('[data-products-grid]');
    if (!grids.length) return;

    grids.forEach((grid) => {
      const source = grid.dataset.productsSource || 'all';
      const limit = Number(grid.dataset.productsLimit || 0);
      let list = [...products];

      if (source === 'popular') {
        list = list.filter((product) => product.popular);
      }

      if (source === 'listing') {
        return;
      }

      if (limit) {
        list = list.slice(0, limit);
      }

      grid.innerHTML = list.map((product) => productCardTemplate(product)).join('');
    });

    refreshAllProductCards();
  };

  const renderDealsSlider = () => {
    const slider = qs('[data-deals-slider]');
    if (!slider) return;
    const limit = Number(slider.dataset.productsLimit || 0);
    let list = products.filter((product) => product.isDeal);
    if (limit) list = list.slice(0, limit);
    const countEl = qs('[data-deals-count]');
    if (countEl) {
      countEl.textContent = list.length;
    }
    slider.innerHTML = list.map((product) => productCardTemplate(product, 'slider')).join('');
    refreshAllProductCards();
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

  const getListingFilters = () => {
    const selected = qsa('[data-filter-category]:checked').map((input) => input.value);
    const sortValue = qs('[data-sort]') ? qs('[data-sort]').value : 'featured';
    return { categories: selected, sort: sortValue };
  };

  const renderListingGrid = () => {
    const grid = qs('[data-products-grid][data-products-source=\"listing\"]');
    if (!grid) return;
    let list = [...products];
    const { categories, sort } = getListingFilters();

    if (categories.length) {
      list = list.filter((product) => categories.includes(product.category));
    }

    if (sort === 'price-low') {
      list.sort((a, b) => a.units[0].price - b.units[0].price);
    } else if (sort === 'price-high') {
      list.sort((a, b) => b.units[0].price - a.units[0].price);
    } else if (sort === 'rating') {
      list.sort((a, b) => b.rating - a.rating);
    }

    grid.innerHTML = list.map((product) => productCardTemplate(product)).join('');

    const countEl = qs('[data-results-count]');
    if (countEl) {
      countEl.textContent = list.length;
    }

    refreshAllProductCards();
  };

  const updateDetailPrice = (detail) => {
    const select = qs('[data-detail-unit-select]', detail);
    const option = select.selectedOptions[0];
    const price = Number(option.dataset.price || 0);
    const oldPrice = Number(option.dataset.oldPrice || 0);
    qs('[data-detail-price]', detail).textContent = formatCurrency(price);
    const oldEl = qs('[data-detail-old-price]', detail);
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
    const params = new URLSearchParams(window.location.search);
    const productId = params.get('id') || products[0].id;
    const product = findProduct(productId) || products[0];

    detail.dataset.productId = product.id;

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
      descriptionEl.textContent = product.description;
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

    const images = [
      product.image,
      `https://picsum.photos/seed/${product.id}-alt1/800/600`,
      `https://picsum.photos/seed/${product.id}-alt2/800/600`,
      `https://picsum.photos/seed/${product.id}-alt3/800/600`,
      `https://picsum.photos/seed/${product.id}-alt4/800/600`,
      `https://picsum.photos/seed/${product.id}-alt5/800/600`
    ];

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

    qs('[data-tab="description"]').textContent = product.description;
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
              <a href="product-details.html?id=${product.id}" class="text-sm font-semibold text-slate-900 hover:text-green-700">${product.name}</a>
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

    const unitSelect = event.target.closest('[data-unit-select]');
    if (unitSelect) {
      const card = unitSelect.closest('[data-product-card]');
      if (card) {
        updateUnitDisplay(card);
        syncCardState(card);
      }
    }

    const detailUnit = event.target.closest('[data-detail-unit-select]');
    if (detailUnit) {
      const detail = detailUnit.closest('[data-product-detail]');
      updateDetailPrice(detail);
      syncDetailState(detail);
    }

    if (event.target.closest('[data-sort]') || event.target.closest('[data-filter-category]')) {
      renderListingGrid();
    }
  });

  document.addEventListener('click', (event) => {
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

    const wishlistButton = event.target.closest('[data-action="wishlist"]');
    if (wishlistButton) {
      const card = wishlistButton.closest('[data-product-card]');
      if (card) {
        toggleWishlist(card.dataset.productId);
        refreshAllProductCards();
      }
      return;
    }

    const quickViewButton = event.target.closest('[data-action="quick-view"]');
    if (quickViewButton) {
      const card = quickViewButton.closest('[data-product-card]');
      if (card) {
        const id = card.dataset.productId;
        const unitSelect = qs('[data-unit-select]', card);
        const unit = unitSelect ? unitSelect.value : null;
        openProductModal(id, { mode: 'quick', unit });
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

    const addButton = event.target.closest('[data-action="add"]');
    if (addButton) {
      const modal = addButton.closest('[data-modal]');
      const card = addButton.closest('[data-product-card]');
      const detail = addButton.closest('[data-product-detail]');

      if (modal) {
        const productId = modal.dataset.productId || modalState.activeProductId;
        const unitInput = qs('[data-modal-unit]:checked', modal) || qs('[data-modal-unit]', modal);
        const qtyEl = qs('[data-modal-qty]', modal);
        const unit = unitInput ? unitInput.value : null;
        const qty = qtyEl ? Number(qtyEl.textContent || 1) : 1;

        if (productId && unit) {
          addToCart(productId, unit, qty);
          setCardUnitSelection(productId, unit);
        }

        closeProductModal();
        updateCartBadges();
        refreshAllProductCards();
        return;
      }

      if (card) {
        const id = card.dataset.productId;
        const product = findProduct(id);
        const unitSelect = qs('[data-unit-select]', card);
        const unit = unitSelect ? unitSelect.value : null;

        if (product && product.units.length > 1) {
          openProductModal(id, { mode: 'options', unit, focusUnits: true });
          return;
        }

        if (unit) {
          addToCart(id, unit);
          syncCardState(card);
        }
      }

      if (detail) {
        const id = detail.dataset.productId;
        const unit = qs('[data-detail-unit-select]', detail).value;
        addToCart(id, unit);
        syncDetailState(detail);
      }

      updateCartBadges();
      refreshAllProductCards();
      return;
    }

    const incButton = event.target.closest('[data-action="inc"]');
    if (incButton) {
      const cartItem = incButton.closest('[data-cart-item]');
      const card = incButton.closest('[data-product-card]');
      const detail = incButton.closest('[data-product-detail]');

      if (cartItem) {
        updateCartItem(cartItem.dataset.productId, cartItem.dataset.unit, 1);
        renderCart();
        renderCheckoutSummary();
      } else if (card) {
        const id = card.dataset.productId;
        const unit = qs('[data-unit-select]', card).value;
        updateCartItem(id, unit, 1);
        syncCardState(card);
      } else if (detail) {
        const id = detail.dataset.productId;
        const unit = qs('[data-detail-unit-select]', detail).value;
        updateCartItem(id, unit, 1);
        syncDetailState(detail);
      }

      updateCartBadges();
      refreshAllProductCards();
      return;
    }

    const decButton = event.target.closest('[data-action="dec"]');
    if (decButton) {
      const cartItem = decButton.closest('[data-cart-item]');
      const card = decButton.closest('[data-product-card]');
      const detail = decButton.closest('[data-product-detail]');

      if (cartItem) {
        updateCartItem(cartItem.dataset.productId, cartItem.dataset.unit, -1);
        renderCart();
        renderCheckoutSummary();
      } else if (card) {
        const id = card.dataset.productId;
        const unit = qs('[data-unit-select]', card).value;
        updateCartItem(id, unit, -1);
        syncCardState(card);
      } else if (detail) {
        const id = detail.dataset.productId;
        const unit = qs('[data-detail-unit-select]', detail).value;
        updateCartItem(id, unit, -1);
        syncDetailState(detail);
      }

      updateCartBadges();
      refreshAllProductCards();
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
        refreshAllProductCards();
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
    const modal = modalState.element || qs('[data-modal="product"]');
    if (!modal || modal.classList.contains('hidden')) return;
    closeProductModal();
  });

  ensureProductModal();
  initMagnify();
  updateNavActive();
  initStickyHeader();
  initHeroSlider();
  initCountdowns();
  renderDealsSlider();
  initDealsCarousel();
  renderProductCollections();
  renderListingGrid();
  renderProductDetails();
  renderCart();
  renderCheckoutSummary();
  updateCartBadges();
  initScrollReveal();
})();
