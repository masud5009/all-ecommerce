(function () {
    'use strict';

    const pluginSettings = window.websitePlugins || {};

    const getCurrencyCode = () => {
        const candidate = String(pluginSettings.currencyCode || '').trim().toUpperCase();
        return candidate.length === 3 ? candidate : 'BDT';
    };

    const normalizeNumber = (value) => {
        const numeric = Number(value);
        return Number.isFinite(numeric) ? numeric : 0;
    };

    const normalizeItems = (items) => {
        return (Array.isArray(items) ? items : []).map((item) => ({
            item_id: String(item.item_id ?? item.id ?? ''),
            item_name: item.item_name || item.name || 'Product',
            item_variant: item.item_variant || item.variant_label || item.variant || undefined,
            price: normalizeNumber(item.price),
            quantity: Math.max(1, parseInt(item.quantity, 10) || 1),
        }));
    };

    const trackGoogleEvent = (eventName, params) => {
        if (typeof window.gtag === 'function') {
            window.gtag('event', eventName, params);
        }
    };

    const trackFacebookEvent = (eventName, params) => {
        if (typeof window.fbq === 'function') {
            window.fbq('track', eventName, params);
        }
    };

    const renderGoogleRecaptchaWidgets = (scope = document) => {
        if (!pluginSettings.googleRecaptchaEnabled || typeof window.grecaptcha === 'undefined') {
            return;
        }

        scope.querySelectorAll('.js-google-recaptcha').forEach((element) => {
            if (element.dataset.widgetId) {
                return;
            }

            const siteKey = element.dataset.sitekey || pluginSettings.googleRecaptchaSiteKey;
            if (!siteKey) {
                return;
            }

            const widgetId = window.grecaptcha.render(element, {
                sitekey: siteKey,
                theme: element.dataset.theme || 'light',
            });

            element.dataset.widgetId = String(widgetId);
        });
    };

    const resetGoogleRecaptcha = (scope = document) => {
        if (!pluginSettings.googleRecaptchaEnabled || typeof window.grecaptcha === 'undefined') {
            return;
        }

        scope.querySelectorAll('.js-google-recaptcha').forEach((element) => {
            if (element.dataset.widgetId) {
                window.grecaptcha.reset(Number(element.dataset.widgetId));
            }
        });
    };

    window.initGoogleRecaptchaWidgets = renderGoogleRecaptchaWidgets;
    window.resetGoogleRecaptcha = resetGoogleRecaptcha;
    window.onGoogleRecaptchaLoaded = function () {
        renderGoogleRecaptchaWidgets(document);
    };

    document.addEventListener('DOMContentLoaded', () => {
        renderGoogleRecaptchaWidgets(document);
    });

    window.addEventListener('freshcart:add_to_cart', (event) => {
        const detail = event.detail || {};
        const currency = detail.currency || getCurrencyCode();
        const quantity = Math.max(1, parseInt(detail.quantity, 10) || 1);
        const price = normalizeNumber(detail.price);
        const value = normalizeNumber(detail.value || price * quantity);
        const items = normalizeItems([
            {
                item_id: detail.productId,
                item_name: detail.name,
                item_variant: detail.variantLabel,
                price,
                quantity,
            },
        ]);

        trackGoogleEvent('add_to_cart', {
            currency,
            value,
            items,
        });

        trackFacebookEvent('AddToCart', {
            content_ids: items.map((item) => item.item_id),
            content_type: 'product',
            value,
            currency,
        });
    });

    window.addEventListener('freshcart:begin_checkout', (event) => {
        const detail = event.detail || {};
        const currency = detail.currency || getCurrencyCode();
        const items = normalizeItems(detail.items);
        const value = normalizeNumber(detail.value);

        trackGoogleEvent('begin_checkout', {
            currency,
            value,
            items,
        });

        trackFacebookEvent('InitiateCheckout', {
            content_ids: items.map((item) => item.item_id),
            content_type: 'product',
            num_items: items.reduce((total, item) => total + item.quantity, 0),
            value,
            currency,
        });
    });

    window.addEventListener('freshcart:purchase', (event) => {
        const detail = event.detail || {};
        const currency = detail.currency || getCurrencyCode();
        const items = normalizeItems(detail.items);
        const value = normalizeNumber(detail.value);
        const shipping = normalizeNumber(detail.shipping);

        trackGoogleEvent('purchase', {
            transaction_id: detail.transactionId || detail.orderNumber || '',
            value,
            shipping,
            currency,
            items,
        });

        trackFacebookEvent('Purchase', {
            content_ids: items.map((item) => item.item_id),
            content_type: 'product',
            value,
            currency,
        });
    });
})();
