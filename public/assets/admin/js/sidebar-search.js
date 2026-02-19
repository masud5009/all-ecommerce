(function () {
    "use strict";

    var modalElement = document.getElementById("sidebarSearchModal");
    if (!modalElement) {
        return;
    }

    var searchInput = modalElement.querySelector("#sidebarSearchInput");
    var resultsContainer = modalElement.querySelector("#sidebarSearchResults");
    if (!searchInput || !resultsContainer) {
        return;
    }

    var defaultEmptyText = resultsContainer.getAttribute("data-empty-default") || "No sidebar menu found.";
    var noResultText = resultsContainer.getAttribute("data-empty-noresult") || "No sidebar menu matched your search.";
    var sidebarItems = [];

    function normalizeText(value) {
        return (value || "").replace(/\s+/g, " ").trim().toLowerCase();
    }

    function getMenuLabel(link) {
        var span = link.querySelector("span");
        var text = span ? span.textContent : link.textContent;

        return (text || "").replace(/\s+/g, " ").trim();
    }

    function getDirectNavLink(navItem) {
        if (!navItem || !navItem.children) {
            return null;
        }

        for (var i = 0; i < navItem.children.length; i += 1) {
            var child = navItem.children[i];
            if (child.classList && child.classList.contains("nav-link")) {
                return child;
            }
        }

        return null;
    }

    function getParentLabel(link) {
        var submenu = link.closest(".nav-group-sub");
        if (!submenu) {
            return "";
        }

        var navItem = submenu.closest(".nav-item");
        var parentLink = getDirectNavLink(navItem);

        return parentLink ? getMenuLabel(parentLink) : "";
    }

    function getIconClass(link) {
        var icon = link.querySelector("i");
        if (icon && icon.className) {
            return icon.className;
        }

        var submenu = link.closest(".nav-group-sub");
        if (!submenu) {
            return "fas fa-link";
        }

        var parentLink = getDirectNavLink(submenu.closest(".nav-item"));
        var parentIcon = parentLink ? parentLink.querySelector("i") : null;

        return parentIcon && parentIcon.className ? parentIcon.className : "fas fa-link";
    }

    function buildSidebarIndex() {
        var links = document.querySelectorAll(".sidebar-main .nav-sidebar a.nav-link");
        var seen = new Set();

        sidebarItems = [];

        links.forEach(function (link) {
            var href = (link.getAttribute("href") || "").trim();
            if (!href || href === "#" || href.indexOf("javascript:") === 0) {
                return;
            }

            var title = getMenuLabel(link);
            if (!title) {
                return;
            }

            var parentLabel = getParentLabel(link);
            var item = {
                title: title,
                titleNormalized: normalizeText(title),
                parentLabel: parentLabel,
                parentNormalized: normalizeText(parentLabel),
                href: href,
                iconClass: getIconClass(link)
            };
            item.searchText = normalizeText(title + " " + parentLabel);

            var uniqueKey = item.titleNormalized + "|" + item.href;
            if (seen.has(uniqueKey)) {
                return;
            }

            seen.add(uniqueKey);
            sidebarItems.push(item);
        });

        sidebarItems.sort(function (a, b) {
            return a.title.localeCompare(b.title);
        });
    }

    function createEmptyState(text) {
        var empty = document.createElement("div");
        empty.className = "sidebar-search-empty";
        empty.textContent = text;
        return empty;
    }

    function createResultRow(item) {
        var link = document.createElement("a");
        link.className = "sidebar-search-item";
        link.href = item.href;
        link.setAttribute("data-bs-dismiss", "modal");

        var leftSide = document.createElement("span");
        leftSide.className = "sidebar-search-item-main";

        var iconWrap = document.createElement("span");
        iconWrap.className = "sidebar-search-item-icon";

        var icon = document.createElement("i");
        icon.className = item.iconClass;
        iconWrap.appendChild(icon);

        var textWrap = document.createElement("span");
        textWrap.className = "sidebar-search-item-text";

        var title = document.createElement("span");
        title.className = "sidebar-search-item-title";
        title.textContent = item.title;
        textWrap.appendChild(title);

        if (item.parentLabel) {
            var parent = document.createElement("span");
            parent.className = "sidebar-search-item-parent";
            parent.textContent = item.parentLabel;
            textWrap.appendChild(parent);
        }

        leftSide.appendChild(iconWrap);
        leftSide.appendChild(textWrap);

        var rightSide = document.createElement("span");
        rightSide.className = "sidebar-search-item-arrow";

        var arrow = document.createElement("i");
        arrow.className = "fas fa-arrow-right";
        rightSide.appendChild(arrow);

        link.appendChild(leftSide);
        link.appendChild(rightSide);

        return link;
    }

    function getMatchRank(item, query) {
        if (!query) {
            return 0;
        }

        if (item.titleNormalized === query) {
            return 0;
        }

        if (item.titleNormalized.indexOf(query) === 0) {
            return 1;
        }

        if (item.titleNormalized.indexOf(query) > -1) {
            return 2;
        }

        if (item.parentNormalized.indexOf(query) === 0) {
            return 3;
        }

        return 4;
    }

    function getFilteredItems(query) {
        var normalizedQuery = normalizeText(query);
        var filtered = sidebarItems.slice();

        if (normalizedQuery) {
            filtered = filtered.filter(function (item) {
                return item.searchText.indexOf(normalizedQuery) > -1;
            });
        }

        filtered.sort(function (a, b) {
            var rankDiff = getMatchRank(a, normalizedQuery) - getMatchRank(b, normalizedQuery);
            if (rankDiff !== 0) {
                return rankDiff;
            }

            return a.title.localeCompare(b.title);
        });

        return filtered;
    }

    function renderResults(query) {
        var filtered = getFilteredItems(query);
        var fragment = document.createDocumentFragment();

        resultsContainer.innerHTML = "";

        if (!sidebarItems.length) {
            resultsContainer.appendChild(createEmptyState(defaultEmptyText));
            return;
        }

        if (!filtered.length) {
            resultsContainer.appendChild(createEmptyState(noResultText));
            return;
        }

        filtered.forEach(function (item) {
            fragment.appendChild(createResultRow(item));
        });

        resultsContainer.appendChild(fragment);
    }

    searchInput.addEventListener("input", function () {
        renderResults(searchInput.value);
    });

    searchInput.addEventListener("keydown", function (event) {
        if (event.key !== "Enter") {
            return;
        }

        var firstItem = resultsContainer.querySelector(".sidebar-search-item");
        if (!firstItem) {
            return;
        }

        event.preventDefault();
        firstItem.click();
    });

    modalElement.addEventListener("shown.bs.modal", function () {
        buildSidebarIndex();
        searchInput.value = "";
        renderResults("");

        window.setTimeout(function () {
            searchInput.focus();
        }, 60);
    });

    modalElement.addEventListener("hidden.bs.modal", function () {
        searchInput.value = "";
        resultsContainer.innerHTML = "";
    });
})();
