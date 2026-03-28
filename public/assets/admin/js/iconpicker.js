"use strict";

document.addEventListener('DOMContentLoaded', function () {
    const icons = [
        "fa-address-book", "fa-address-card", "fa-anchor", "fa-archive", "fa-balance-scale",
        "fa-ban", "fa-bars", "fa-battery-full", "fa-bed", "fa-bell", "fa-bicycle",
        "fa-binoculars", "fa-birthday-cake", "fa-bluetooth", "fa-bolt", "fa-bomb",
        "fa-book", "fa-bookmark", "fa-briefcase", "fa-bug", "fa-building",
        "fa-bullhorn", "fa-bullseye", "fa-bus", "fa-calculator", "fa-calendar",
        "fa-camera", "fa-car", "fa-caret-down", "fa-caret-up", "fa-chart-bar",
        "fa-check", "fa-check-circle", "fa-check-square", "fa-child", "fa-church",
        "fa-circle", "fa-clipboard", "fa-clock", "fa-clone", "fa-cloud",
        "fa-code", "fa-coffee", "fa-cog", "fa-cogs", "fa-columns",
        "fa-comment", "fa-comments", "fa-compass", "fa-compress", "fa-copy",
        "fa-credit-card", "fa-crop", "fa-crosshairs", "fa-cube", "fa-database",
        "fa-desktop", "fa-dollar-sign", "fa-download", "fa-edit", "fa-ellipsis-h",
        "fa-envelope", "fa-exclamation", "fa-exclamation-circle", "fa-exclamation-triangle", "fa-expand",
        "fa-external-link-alt", "fa-eye", "fa-eye-slash", "fa-fast-backward", "fa-fast-forward",
        "fa-fax", "fa-female", "fa-fighter-jet", "fa-file", "fa-film",
        "fa-filter", "fa-fire", "fa-flag", "fa-flask", "fa-folder",
        "fa-font", "fa-forward", "fa-gamepad", "fa-gavel", "fa-gem",
        "fa-gift", "fa-glass-martini", "fa-globe", "fa-graduation-cap", "fa-h-square",
        "fa-hand-paper", "fa-handshake", "fa-hashtag", "fa-headphones", "fa-heart",
        "fa-home", "fa-hospital", "fa-hourglass", "fa-id-badge", "fa-id-card",
        "fa-image", "fa-inbox", "fa-industry", "fa-info", "fa-info-circle",
        "fa-key", "fa-keyboard", "fa-language", "fa-laptop", "fa-leaf",
        "fa-lemon", "fa-level-up-alt", "fa-life-ring", "fa-lightbulb", "fa-link",
        "fa-location-arrow", "fa-lock", "fa-magic", "fa-magnet", "fa-map",
        "fa-map-marker", "fa-medkit", "fa-meh", "fa-microphone", "fa-minus",
        "fa-mobile", "fa-money-bill", "fa-moon", "fa-motorcycle", "fa-mouse-pointer",
        "fa-music", "fa-newspaper", "fa-paper-plane", "fa-paperclip", "fa-paste",
        "fa-pause", "fa-pencil-alt", "fa-phone", "fa-plane", "fa-play",
        "fa-plus", "fa-poo", "fa-power-off", "fa-print", "fa-puzzle-piece",
        "fa-question", "fa-question-circle", "fa-quote-left", "fa-random", "fa-recycle",
        "fa-redo", "fa-registered", "fa-reply", "fa-retweet", "fa-road",
        "fa-rocket", "fa-save", "fa-search", "fa-server", "fa-share",
        "fa-shield-alt", "fa-ship", "fa-shopping-bag", "fa-shopping-cart", "fa-shower",
        "fa-sign-in-alt", "fa-sign-out-alt", "fa-signal", "fa-sitemap", "fa-skull",
        "fa-sliders-h", "fa-smile", "fa-snowflake", "fa-sort", "fa-space-shuttle",
        "fa-star", "fa-star-half", "fa-step-backward", "fa-step-forward", "fa-stethoscope",
        "fa-sticky-note", "fa-stop", "fa-store", "fa-street-view", "fa-subway",
        "fa-suitcase", "fa-sun", "fa-sync", "fa-table", "fa-tablet",
        "fa-tag", "fa-tags", "fa-tasks", "fa-taxi", "fa-terminal",
        "fa-thermometer-half", "fa-thumbs-down", "fa-thumbs-up", "fa-thumbtack", "fa-ticket-alt",
        "fa-times", "fa-tools", "fa-trash", "fa-tree", "fa-trophy",
        "fa-truck", "fa-tv", "fa-umbrella", "fa-university", "fa-unlock",
        "fa-upload", "fa-user", "fa-user-circle", "fa-user-md", "fa-user-plus",
        "fa-users", "fa-utensils", "fa-video", "fa-volume-up", "fa-wifi",
        "fa-wrench", "fa-apple", "fa-airbnb", "fa-alipay", "fa-angellist", "fa-angular",
        "fa-archway", "fa-anchor-circle-check", "fa-anchor-circle-exclamation",
        "fa-anchor-circle-xmark", "fa-anchor-lock", "fa-air-freshener",
        "fa-allergies", "fa-artstation", "fa-asymmetrik", "fa-atlas",
        "fa-atom", "fa-audio-description", "fa-autoprefixer", "fa-avianex",
        "fa-award", "fa-aws", "fa-backward-step", "fa-badge-check", "fa-badge‑percent",
        "fa-badminton", "fa-bandage", "fa-bandcamp", "fa-bank", "fa-barcode‑read",
        "fa-basketball", "fa-basketball-ball", "fa-bath", "fa-battery‑empty",
        "fa-battery‑quarter", "fa-battery‑three‑quarters", "fa-bell‑school",
        "fa-bell‑school‑slash", "fa-bench‑tree", "fa-bento", "fa-bicycle‑ball",
        "fa-biking", "fa-binance", "fa-biohazard", "fa-birthday‑cake‑candles",
        "fa-bitcoin‑sign", "fa-blender", "fa-blind", "fa-blog", "fa-blogger‑b",
        "fa-bluetooth‑b", "fa-bold‑italic", "fa-bolt‑lightning", "fa-bone‑break",
        "fa-book‑open‑cover", "fa-book‑quran", "fa-book‑skull", "fa-bookshelf",
        "fa-boombox", "fa-boot", "fa-bottle‑water", "fa-bow‑arrow", "fa-bowling‑ball",
        "fa-box‑archive", "fa-box‑open‑full", "fa-box‑up", "fa-brackets‑curly",
        "fa-brain", "fa-brick‑wall", "fa-bring‑forward", "fa-broom‑ball", "fa-browser",
        "fa-brush‑paint", "fa-bucket‑droplet", "fa-building‑circle‑arrow",
        "fa-bull‑bullhorn", "fa-bullseye‑arrow", "fa-burger‑cheese", "fa-burst",
        "fa-business‑time", "fa-cake‑candles", "fa-calculator‑simple", "fa-calendar‑day",
        "fa-calendar‑exclamation", "fa-camera‑polaroid", "fa-campground",
        "fa-cannabis‑leaf", "fa-capsules‑medication", "fa-car‑battery‑charging",
        "fa-car‑side‑collision", "fa-car‑wash‑machine", "fa-caret‑circle‑down",
        "fa-caret‑circle‑up", "fa-cart‑flatbed‑suitcase", "fa-cart‑shopping‑fast",
        "fa-cash‑register", "fa-castle‑turret", "fa-categories", "fa-cat‑space",
        "fa-cauldron", "fa-certificate‑badge", "fa-chair‑office", "fa-chalkboard‑user",
        "fa-chalkboard‑teacher", "fa-chalkboard‑simple", "fa-charging‑station",
        "fa-chart‑candlestick", "fa-chart‑scatter", "fa-check‑double", "fa-cheese‑slice",
        "fa-chess‑rook", "fa-chevron‑circle‑left", "fa-chevron‑circle‑right",
        "fa-chess‑knight"
    ];

    function initIconPicker(selectedIcon, inputIcon, iconSearch, iconList) {
        if (!selectedIcon || !inputIcon || !iconSearch || !iconList) {
            return;
        }

        iconList.innerHTML = '';

        const setSelectedIcon = function (iconClass) {
            const normalizedIconClass = (iconClass || '').trim() || 'fas fa-seedling';
            selectedIcon.className = normalizedIconClass;
            inputIcon.value = normalizedIconClass;
        };

        const defaultIcon = inputIcon.value || selectedIcon.className;
        setSelectedIcon(defaultIcon);

        icons.forEach(function (icon) {
            const iconElement = document.createElement('div');
            iconElement.className = 'iconpicker';
            iconElement.style.cursor = 'pointer';
            iconElement.innerHTML = `<i class="fas ${icon} fa-lg"></i>`;
            iconElement.addEventListener('click', function () {
                setSelectedIcon(`fas ${icon}`);
            });
            iconList.appendChild(iconElement);
        });

        iconSearch.addEventListener('input', function () {
            const searchTerm = iconSearch.value.toLowerCase();
            const allIcons = iconList.querySelectorAll('div');

            allIcons.forEach(function (icon) {
                const iconClass = icon.querySelector('i').className.toLowerCase();
                icon.style.display = iconClass.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    const pickerWrappers = document.querySelectorAll('.js-iconpicker');
    if (pickerWrappers.length > 0) {
        pickerWrappers.forEach(function (wrapper) {
            const selectedIcon = wrapper.querySelector('[data-iconpicker-selected]');
            const iconSearch = wrapper.querySelector('[data-iconpicker-search]');
            const iconList = wrapper.querySelector('[data-iconpicker-list]');
            const inputIcon = wrapper.parentElement.querySelector('[data-iconpicker-input]');

            initIconPicker(selectedIcon, inputIcon, iconSearch, iconList);
        });
    } else {
        // Legacy support for pages still using fixed IDs.
        const iconList = document.getElementById('iconList');
        const selectedIcon = document.getElementById('selectedIcon');
        const inputIcon = document.getElementById('inputIcon');
        const iconSearch = document.getElementById('iconSearch');

        initIconPicker(selectedIcon, inputIcon, iconSearch, iconList);
    }
});
