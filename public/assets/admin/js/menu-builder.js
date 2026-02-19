"use strict";
function renderMenuItem(title, url, target, type) {
    const id = 'id_' + Math.random().toString(36).substr(2, 9);
    return `<li class="list-group-item menu-item rounded shadow-sm mb-2" data-type="${type}" data-title="${title}" data-url="${url}" data-target="${target}" id="${id}">
              <div class="d-flex justify-content-between align-items-center">
                <span class="item-label fw-semibold">${title} <small class="text-muted">(${target})</small></span>
                <div class="menu-item-actions product-list-actions">
                  <button type="button" class="btn btn-sm edit product-action-btn menu-action-btn" data-id="${id}" title="Edit">
                    <i class="fa fa-pen"></i><span class="product-action-label">Edit</span>
                  </button>
                  <button type="button" class="btn btn-sm remove product-action-btn menu-action-btn" title="Delete">
                    <i class="fa fa-trash"></i><span class="product-action-label">Delete</span>
                  </button>
                </div>
              </div>
              <ul class="nested list-group mt-2 ps-3"></ul>
            </li>`;
}

function initSortable(el) {
    if (!el) return;

    new Sortable(el, {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        onAdd: function () {
            // Ensure new nested lists are initialized
            $(el).children('li').each(function () {
                const nested = $(this).children('ul.nested')[0];
                if (nested) {
                    initSortable(nested);
                } else {
                    $(this).append('<ul class="nested list-group mt-2 ps-3"></ul>');
                    initSortable($(this).children('ul.nested')[0]);
                }
            });
        }
    });

    $(el).children('li').each(function () {
        const nested = $(this).children('ul.nested')[0];
        if (nested) {
            initSortable(nested);
        }
    });
}

$(document).on('click', '.addToMenu', function () {
    const item = $(this).closest('li');
    const title = item.data('title');
    const url = item.data('url');
    const target = item.data('target');
    const $newItem = $(renderMenuItem(title, url, target, "prebuilt"));
    $('#menuBuilder').append($newItem);
    initSortable($newItem.find('ul.nested')[0]);
});

$('#addCustomMenu').click(function () {
    const title = $('#menuTitle').val();
    const url = $('#menuUrl').val();
    if (!title) {
        $('#err_menuTitle').removeClass('d-none').text('The menu title field is required');
    }
    if (!url) {
        $('#err_menuUrl').removeClass('d-none').text('The menu url field is required');
    }
    const target = $('#menuTarget').val();
    if (title && url) {
        const $newItem = $(renderMenuItem(title, url, target, 'custom'));
        $('#menuBuilder').append($newItem);
        $('#customMenuModal').modal('hide');
        $('#menuTitle, #menuUrl').val('');
        initSortable($newItem.find('ul.nested')[0]);
    }
});

$(document).on('click', '.remove', function () {
    $(this).closest('li').remove();
});

$(document).on('click', '.edit', function () {
    const id = $(this).data('id');
    const item = $('#' + id);
    $('#editItemId').val(id);
    $('#editTitle').val(item.data('title'));
    $('#editUrl').val(item.data('url'));
    $('#editTarget').val(item.data('target'));
    $('#editMenuModal').modal('show');
});

$('#updateMenuItem').click(function () {
    const id = $('#editItemId').val();
    const title = $('#editTitle').val();
    const url = $('#editUrl').val();

    if (!title) {
        $('#eerr_menuTitle').removeClass('d-none').text('The menu title field is required');
        return;
    }
    if (!url) {
        $('#eerr_menuUrl').removeClass('d-none').text('The menu url field is required');
        return;
    }

    const target = $('#editTarget').val();
    const item = $('#' + id);
    item.attr('data-title', title);
    item.attr('data-url', url);
    item.attr('data-target', target);
    item.find('.item-label').html(`${title} <small class="text-muted">(${target})</small>`);
    $('#editMenuModal').modal('hide');
});

function buildMenuJson($list) {
    const items = [];
    $list.children('li').each(function () {
        const $this = $(this);
        items.push({
            title: $this.data('title'),
            url: $this.data('url'),
            target: $this.data('target'),
            type: $this.data('type'),
            children: buildMenuJson($this.children('ul.nested'))
        });
    });
    return items;
}

$('#saveMenu').click(function () {
    const output = buildMenuJson($('#menuBuilder'));

    $.ajax({
        url: updateUrl,
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
            menu: output
        },
        success: function (response) {
            if (response.status === 'success') {
                location.reload();
            }
        }
    });
});


initSortable(document.getElementById('menuBuilder'));
