/**
 * Cart functionality - No HTML in JS
 * All cart operations via AJAX with server-rendered content
 */
(function () {
    'use strict';

    const CART_ROUTES = {
        index: '/cart',
        add: '/cart/add',
        update: '/cart/update',
        remove: '/cart/remove',
        clear: '/cart/clear',
    };

    // CSRF token
    const getCsrfToken = () => {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    };

    // Update all cart count badges
    const updateCartBadges = (count) => {
        document.querySelectorAll('[data-cart-count]').forEach((el) => {
            el.textContent = count;
            if (count > 0) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        });
    };

    // Format currency
    const formatCurrency = (amount) => {
        return '৳' + parseFloat(amount).toFixed(2);
    };

    // Fetch cart and render to offcanvas
    const fetchAndRenderCart = async () => {
        try {
            const response = await fetch(CART_ROUTES.index, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (data.success) {
                updateCartBadges(data.totalQty);
                renderCartFromServer(data.html, data.totalPrice, data.totalQty);
            }
        } catch (error) {
            console.error('Error fetching cart:', error);
        }
    };

    // Render server-provided cart HTML
    const renderCartFromServer = (html, totalPrice, totalQty) => {
        const container = document.querySelector('[data-cart-items]');
        const totalEl = document.querySelector('[data-cart-total]');
        const emptyEl = document.querySelector('[data-cart-empty]');
        const listEl = document.querySelector('[data-cart-list]');
        const footerEl = document.querySelector('[data-cart-footer]');

        if (!container) return;

        // Insert server-rendered HTML
        container.innerHTML = html;

        if (totalQty === 0) {
            if (emptyEl) emptyEl.classList.remove('hidden');
            if (listEl) listEl.classList.add('hidden');
            if (footerEl) footerEl.classList.add('hidden');
        } else {
            if (emptyEl) emptyEl.classList.add('hidden');
            if (listEl) listEl.classList.remove('hidden');
            if (footerEl) footerEl.classList.remove('hidden');
        }

        if (totalEl) {
            totalEl.textContent = formatCurrency(totalPrice);
        }
    };

    // Add to cart
    const addToCart = async (productId, quantity, variantId, variantLabel, price, meta = {}) => {
        try {
            const response = await fetch(CART_ROUTES.add, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity,
                    variant_id: variantId,
                    variant_label: variantLabel,
                    price: price,
                }),
            });

            const data = await response.json();

            if (data.success) {
                updateCartBadges(data.totalQty);
                showToast('Added to cart!', 'success');
                window.dispatchEvent(new CustomEvent('freshcart:add_to_cart', {
                    detail: {
                        productId: productId,
                        quantity: quantity,
                        variantId: variantId,
                        variantLabel: variantLabel,
                        price: price,
                        value: Number(price || 0) * Number(quantity || 1),
                        name: meta.name || null,
                    },
                }));
                // Refresh cart items
                fetchAndRenderCart();
                // Open cart offcanvas
                openCartOffcanvas();
            } else {
                showToast(data.message || 'Failed to add to cart', 'error');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            showToast('Error adding to cart', 'error');
        }
    };

    // Update cart item quantity
    const updateCartItem = async (cartId, quantity) => {
        try {
            const response = await fetch(CART_ROUTES.update, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    quantity: quantity,
                }),
            });

            const data = await response.json();

            if (data.success) {
                updateCartBadges(data.totalQty);
                // Update subtotal
                const subtotalEl = document.querySelector(`[data-item-subtotal="${cartId}"]`);
                if (subtotalEl) {
                    subtotalEl.textContent = formatCurrency(data.itemSubtotal);
                }
                // Update total
                const totalEl = document.querySelector('[data-cart-total]');
                if (totalEl) {
                    totalEl.textContent = formatCurrency(data.totalPrice);
                }
                // Update quantity display
                const qtyEl = document.querySelector(`[data-qty-val="${cartId}"]`);
                if (qtyEl) {
                    qtyEl.textContent = quantity;
                }
            }
        } catch (error) {
            console.error('Error updating cart:', error);
        }
    };

    // Remove cart item
    const removeCartItem = async (cartId) => {
        try {
            const response = await fetch(CART_ROUTES.remove, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    cart_id: cartId,
                }),
            });

            const data = await response.json();

            if (data.success) {
                updateCartBadges(data.totalQty);
                // Remove item from DOM
                const itemEl = document.querySelector(`[data-cart-item="${cartId}"]`);
                if (itemEl) {
                    itemEl.remove();
                }
                // Update total
                const totalEl = document.querySelector('[data-cart-total]');
                if (totalEl) {
                    totalEl.textContent = formatCurrency(data.totalPrice);
                }
                // Check if cart is empty
                if (data.totalQty === 0) {
                    fetchAndRenderCart();
                }
                showToast('Item removed', 'success');
            }
        } catch (error) {
            console.error('Error removing from cart:', error);
        }
    };

    // Toast notification
    const showToast = (message, type = 'success') => {
        const existing = document.querySelector('.cart-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `cart-toast fixed bottom-20 left-1/2 -translate-x-1/2 z-50 px-4 py-2 rounded-full text-sm font-medium shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    };

    // Open cart offcanvas
    const openCartOffcanvas = () => {
        const offcanvas = document.querySelector('[data-cart-offcanvas]');
        const backdrop = document.querySelector('[data-cart-backdrop]');
        if (offcanvas) {
            offcanvas.classList.remove('translate-x-full');
            if (backdrop) backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    // Close cart offcanvas
    const closeCartOffcanvas = () => {
        const offcanvas = document.querySelector('[data-cart-offcanvas]');
        const backdrop = document.querySelector('[data-cart-backdrop]');
        if (offcanvas) {
            offcanvas.classList.add('translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    // Event delegation for cart actions
    document.addEventListener('click', (e) => {
        // Open cart offcanvas
        if (e.target.closest('[data-action="open-cart-offcanvas"]')) {
            e.preventDefault();
            fetchAndRenderCart();
            openCartOffcanvas();
        }

        // Close cart offcanvas
        if (e.target.closest('[data-cart-close]') || e.target.closest('[data-cart-backdrop]')) {
            closeCartOffcanvas();
        }

        // Add to cart from product details page
        if (e.target.closest('[data-action="add"]')) {
            const btn = e.target.closest('[data-action="add"]');
            const productDetailEl = document.querySelector('[data-product-detail]');

            if (productDetailEl && window.serverProductDetail) {
                const productId = window.serverProductDetail.id;
                const qtyInput = document.getElementById('productQty');
                const quantity = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

                // Get selected variant
                const selectedRadio = document.querySelector('input[name="productUnit"]:checked');
                let variantId = null;
                let variantLabel = null;
                let price = window.serverProductDetail.units?.[0]?.price || 0;

                if (selectedRadio) {
                    const index = parseInt(selectedRadio.value);
                    const unit = window.serverProductDetail.units?.[index];
                    if (unit) {
                        variantLabel = unit.label;
                        price = unit.price;
                        // variant_id comes from data attribute if available
                        variantId = selectedRadio.dataset.variantId || null;
                    }
                }

                addToCart(productId, quantity, variantId, variantLabel, price, {
                    name: window.serverProductDetail.name || null,
                });
            }
        }

        // Add to cart from listing cards
        if (e.target.closest('[data-add-to-cart-card]')) {
            e.preventDefault();
            const btn = e.target.closest('[data-add-to-cart-card]');

            if (btn.disabled) {
                return;
            }

            const productId = parseInt(btn.dataset.productId, 10);
            const quantity = parseInt(btn.dataset.quantity || '1', 10) || 1;
            const variantLabel = btn.dataset.variantLabel || null;
            const variantIdRaw = btn.dataset.variantId;
            const variantId = variantIdRaw ? parseInt(variantIdRaw, 10) || null : null;
            const price = parseFloat(btn.dataset.price || '0');

            if (!productId || !Number.isFinite(price)) {
                showToast('Unable to add this item right now', 'error');
                return;
            }

            addToCart(productId, quantity, variantId, variantLabel, price, {
                name: btn.dataset.productName || null,
            });
        }

        // Remove item from cart
        const removeBtn = e.target.closest('[data-remove-item]');
        if (removeBtn) {
            const cartId = removeBtn.dataset.removeItem;
            removeCartItem(cartId);
        }

        // Quantity decrease in cart
        const decBtn = e.target.closest('[data-qty-dec]');
        if (decBtn) {
            const cartId = decBtn.dataset.qtyDec;
            const qtyEl = document.querySelector(`[data-qty-val="${cartId}"]`);
            if (qtyEl) {
                let qty = parseInt(qtyEl.textContent) || 1;
                if (qty > 1) {
                    qty--;
                    updateCartItem(cartId, qty);
                }
            }
        }

        // Quantity increase in cart
        const incBtn = e.target.closest('[data-qty-inc]');
        if (incBtn) {
            const cartId = incBtn.dataset.qtyInc;
            const qtyEl = document.querySelector(`[data-qty-val="${cartId}"]`);
            if (qtyEl) {
                let qty = parseInt(qtyEl.textContent) || 1;
                if (qty < 99) {
                    qty++;
                    updateCartItem(cartId, qty);
                }
            }
        }
    });

    // Initialize - fetch cart count on page load
    document.addEventListener('DOMContentLoaded', () => {
        fetchAndRenderCart();
    });

    // Expose for external use
    window.FreshCart = window.FreshCart || {};
    window.FreshCart.cart = {
        add: addToCart,
        update: updateCartItem,
        remove: removeCartItem,
        refresh: fetchAndRenderCart,
        open: openCartOffcanvas,
        close: closeCartOffcanvas,
    };
})();
