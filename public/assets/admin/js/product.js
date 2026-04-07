"use strict";

(function () {
    const configEl = document.getElementById('productPageConfig');
    if (configEl) {
        const upload = configEl.dataset.uploadSliderImage;
        const remove = configEl.dataset.rmvSliderImage;
        const dbRemove = configEl.dataset.rmvDbSliderImage;

        if (upload) window.uploadSliderImage = upload;
        if (remove) window.rmvSliderImage = remove;
        if (dbRemove) window.rmvDbSliderImage = dbRemove;
    }

    function initSubcategoryBindings() {
        const categorySelects = document.querySelectorAll('select[name$="_category_id"]');

        function syncSubcategoryOptions(categorySelect) {
            const selectedCategory = categorySelect.value;
            const subcategorySelect = document.querySelector(
                `select[name="${categorySelect.name.replace('_category_id', '_subcategory_id')}"]`
            );

            if (!subcategorySelect) return;

            const options = Array.from(subcategorySelect.options);
            let visibleCount = 0;

            options.forEach((option, index) => {
                if (index === 0) {
                    option.hidden = false;
                    return;
                }

                const categoryId = option.dataset.categoryId || '';
                const shouldShow = selectedCategory && categoryId === selectedCategory;

                option.hidden = !shouldShow;
                if (shouldShow) {
                    visibleCount++;
                }
            });

            const selectedOption = subcategorySelect.options[subcategorySelect.selectedIndex];
            const selectedVisible = selectedOption && !selectedOption.hidden;

            if (!selectedVisible) {
                subcategorySelect.value = '';
            }

            subcategorySelect.disabled = !selectedCategory || visibleCount === 0;
        }

        categorySelects.forEach((categorySelect) => {
            categorySelect.addEventListener('change', function () {
                syncSubcategoryOptions(this);
            });

            syncSubcategoryOptions(categorySelect);
        });
    }

    initSubcategoryBindings();

    let existingOptions = [];
    let existingVariants = [];

    if (configEl) {
        const optionsRaw = configEl.dataset.existingOptions;
        const variantsRaw = configEl.dataset.existingVariants;
        try {
            existingOptions = optionsRaw ? JSON.parse(optionsRaw) : [];
        } catch (e) {
            existingOptions = [];
        }
        try {
            existingVariants = variantsRaw ? JSON.parse(variantsRaw) : [];
        } catch (e) {
            existingVariants = [];
        }
    }

    function parseValues(valuesStr) {
        return (valuesStr || '')
            .split(',')
            .map(v => v.trim())
            .filter(v => v.length);
    }

    function cartesian(arrays) {
        return arrays.reduce((acc, curr) => {
            const res = [];
            acc.forEach(a => curr.forEach(b => res.push(a.concat([b]))));
            return res;
        }, [[]]);
    }


    const hasVariantsEl = document.getElementById('has_variants');
    const variationsWrap = document.getElementById('variationsWrap');

    const stockInput = document.querySelector('[name="stock"]');
    const priceInput = document.querySelector('[name="current_price"]');
    const skuInput = document.querySelector('[name="sku"]');

    const optionsList = document.getElementById('optionsList');
    const addOptionBtn = document.getElementById('addOptionBtn');
    const generateVariantsBtn = document.getElementById('generateVariantsBtn');
    const clearVariantsBtn = document.getElementById('clearVariantsBtn');

    const variantsGridWrap = document.getElementById('variantsGridWrap');
    const variantsTbody = document.getElementById('variantsTbody');
    const variantsHiddenInputs = document.getElementById('variantsHiddenInputs');

    if (!optionsList || !addOptionBtn || !generateVariantsBtn || !clearVariantsBtn || !variantsGridWrap || !variantsTbody || !variantsHiddenInputs) {
        return;
    }

    let optionIndex = 0;

    function updateVariantImagePreview(input) {
        const file = input?.files?.[0];
        const row = input.closest('tr');
        if (!row) return;

        const img = row.querySelector('.variant-image-preview');
        const placeholder = row.querySelector('.variant-image-placeholder');
        if (!img || !placeholder) return;

        if (!file) {
            img.classList.add('d-none');
            img.src = '';
            placeholder.classList.remove('d-none');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
            img.classList.remove('d-none');
            placeholder.classList.add('d-none');
        };
        reader.readAsDataURL(file);
    }

    function ensureVariantImageModal() {
        const existing = document.getElementById('variantImageModal');
        if (existing) return existing;

        const modal = document.createElement('div');
        modal.id = 'variantImageModal';
        modal.className = 'modal fade';
        modal.tabIndex = -1;
        modal.setAttribute('aria-hidden', 'true');
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Variant Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="variantImageModalImg" src="" alt="Variant" style="max-width:100%;height:auto;">
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        return modal;
    }

    function openVariantImagePopup(src) {
        if (!src) return;

        const modalEl = ensureVariantImageModal();
        const img = modalEl.querySelector('#variantImageModalImg');
        if (img) img.src = src;

        if (window.bootstrap && window.bootstrap.Modal) {
            const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else {
            window.open(src, '_blank');
        }
    }

    function isSerialInput(input) {
        if (!input) return false;
        return input.matches('input[name$="[serial_start]"], input[name$="[serial_end]"]');
    }

    function syncVariantStockFromSerialRange(row) {
        if (!row) return;

        const serialStartInput = row.querySelector('input[name$="[serial_start]"]');
        const serialEndInput = row.querySelector('input[name$="[serial_end]"]');
        const stockInputEl = row.querySelector('input[name$="[stock]"]');

        if (!serialStartInput || !serialEndInput || !stockInputEl) return;

        const serialStart = (serialStartInput.value || '').trim();
        const serialEnd = (serialEndInput.value || '').trim();

        serialStartInput.setCustomValidity('');
        serialEndInput.setCustomValidity('');

        // Auto-calculate only when both serial fields are present.
        if (!serialStart || !serialEnd) return;

        const isStartNumeric = /^\d+$/.test(serialStart);
        const isEndNumeric = /^\d+$/.test(serialEnd);

        if (!isStartNumeric || !isEndNumeric) {
            if (!isStartNumeric) {
                serialStartInput.setCustomValidity('Serial start must be numeric.');
            }
            if (!isEndNumeric) {
                serialEndInput.setCustomValidity('Serial end must be numeric.');
            }
            return;
        }

        const startValue = BigInt(serialStart);
        const endValue = BigInt(serialEnd);

        if (endValue < startValue) {
            serialEndInput.setCustomValidity('Serial end must be greater than or equal to serial start.');
            return;
        }

        const autoStock = (endValue - startValue + 1n).toString();
        stockInputEl.value = autoStock;
        stockInputEl.dispatchEvent(new Event('input', { bubbles: true }));
    }

    variantsTbody.addEventListener('change', function (e) {
        const input = e.target;
        if (input && input.matches('input[type="file"][name^="variants["]')) {
            updateVariantImagePreview(input);
        }
    });

    variantsTbody.addEventListener('input', function (e) {
        const input = e.target;
        if (isSerialInput(input)) {
            syncVariantStockFromSerialRange(input.closest('tr'));
        }
    });

    variantsTbody.addEventListener('click', function (e) {
        const img = e.target.closest('.variant-image-preview');
        if (!img || img.classList.contains('d-none')) return;
        openVariantImagePopup(img.getAttribute('src'));
    });

    function toggleBaseFields(disabled) {
        if (stockInput) stockInput.disabled = disabled;

        if (priceInput) {
            priceInput.disabled = disabled;
            priceInput.classList.toggle('bg-light', disabled);
        }

        if (skuInput) {
            skuInput.disabled = disabled;
            skuInput.classList.toggle('bg-light', disabled);
        }
    }


    function resetVariations() {
        optionsList.innerHTML = '';
        variantsTbody.innerHTML = '';
        variantsHiddenInputs.innerHTML = '';
        variantsGridWrap.classList.add('d-none');
        clearVariantsBtn.classList.add('d-none');
        optionIndex = 0;
    }

    function showVariations(show) {
        if (!variationsWrap) return;
        variationsWrap.classList.toggle('d-none', !show);
        if (!show) resetVariations();
    }

    function addOptionRow(name = '', values = '') {
        const wrapper = document.createElement('div');
        wrapper.className = 'row align-items-end g-2 mb-2 option-row';
        wrapper.dataset.index = optionIndex;

        wrapper.innerHTML = `
            <div class="col-lg-5">
                <div class="form-group">
                    <label>Option Name</label>
                    <input type="text" class="form-control"
                        name="variant_options[${optionIndex}][name]"
                        placeholder="e.g. Size" value="${name}">
                    <small class="text-muted d-block invisible">Spacer</small>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="form-group">
                    <label>Values</label>
                    <div class="input-group w-100">
                        <input type="text" class="form-control"
                            name="variant_options[${optionIndex}][values]"
                            placeholder="e.g. S, M, L" value="${values}">
                        <button type="button" class="btn btn-outline-danger remove-option btn-sm"
                            title="Remove">&times;</button>
                    </div>
                    <small class="text-muted">Comma separated values</small>
                </div>
            </div>
        `;

        const removeBtn = wrapper.querySelector('.remove-option');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => wrapper.remove());
        }

        optionsList.appendChild(wrapper);
        optionIndex++;
    }

    function renderVariantsFromData(options, variants) {
        variantsTbody.innerHTML = '';
        variantsHiddenInputs.innerHTML = '';

        variants.forEach((variant, i) => {
            const map = variant.map || {};
            const labelParts = options.length ?
                options.map(opt => map[opt.name]).filter(Boolean) :
                Object.values(map);
            const label = labelParts.length ? labelParts.join(' / ') : '-';

            const sku = variant.sku ?? '';
            const imageUrl = variant.image_url ?? '';
            const price = variant.price ?? '';
            const stock = variant.stock ?? 0;
            const status = typeof variant.status === 'undefined' ? 1 : variant.status;
            const serialStart = variant.serial_start ?? '';
            const serialEnd = variant.serial_end ?? '';
            const imagePreview = `
                <img class="variant-image-preview ${imageUrl ? '' : 'd-none'}"
                    src="${imageUrl || ''}" alt="Variant"
                    style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                <span class="variant-image-placeholder text-muted ${imageUrl ? 'd-none' : ''}">No image</span>
            `;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${label}</strong></td>
                <td>
                    <div class="variant-image-cell d-flex align-items-center gap-2">
                        ${imagePreview}
                        <input type="file" class="form-control form-control-sm variant-image-input" name="variants[${i}][image]" accept="image/*">
                    </div>
                </td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][sku]" value="${sku}" placeholder="SKU (optional)"></td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][serial_start]" value="${serialStart}" placeholder="Serial start"></td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][serial_end]" value="${serialEnd}" placeholder="Serial end"></td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][price]" value="${price}" placeholder="Price (optional)"></td>
                <td><input type="number" class="form-control form-control-sm" name="variants[${i}][stock]" min="0" value="${stock}" required></td>
                <td>
                    <select class="form-select form-select-sm" name="variants[${i}][status]">
                        <option value="1" ${status == 1 ? 'selected' : ''}>Active</option>
                        <option value="0" ${status == 0 ? 'selected' : ''}>Inactive</option>
                    </select>
                </td>
            `;
            variantsTbody.appendChild(tr);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = `variants[${i}][map]`;
            hidden.value = JSON.stringify(map);
            variantsHiddenInputs.appendChild(hidden);
        });

        if (variants.length) {
            variantsGridWrap.classList.remove('d-none');
            clearVariantsBtn.classList.remove('d-none');
        }
    }

    if (hasVariantsEl) {
        hasVariantsEl.addEventListener('change', function () {
            const on = !!this.checked;
            toggleBaseFields(on);
            showVariations(on);

            if (on && optionsList.querySelectorAll('.option-row').length === 0) {
                addOptionRow();
            }
        });
    }

    if (hasVariantsEl && hasVariantsEl.checked) {
        toggleBaseFields(true);
        showVariations(true);

        if (existingOptions.length) {
            existingOptions.forEach(opt => addOptionRow(opt.name, opt.values));
        } else if (optionsList.querySelectorAll('.option-row').length === 0) {
            addOptionRow();
        }

        if (existingVariants.length) {
            renderVariantsFromData(existingOptions, existingVariants);
        }
    }

    addOptionBtn.addEventListener('click', () => addOptionRow());

    generateVariantsBtn.addEventListener('click', () => {
        const rows = Array.from(optionsList.querySelectorAll('.option-row'));
        if (rows.length === 0) {
            alert('Please add at least 1 option.');
            return;
        }

        const options = rows.map(r => {
            const name = r.querySelector('input[name*="[name]"]').value.trim();
            const valuesStr = r.querySelector('input[name*="[values]"]').value.trim();
            const values = parseValues(valuesStr);
            return { name, values };
        }).filter(o => o.name && o.values.length);

        if (options.length === 0) {
            alert('Option name and values are required.');
            return;
        }

        const combos = cartesian(options.map(o => o.values));

        variantsTbody.innerHTML = '';
        variantsHiddenInputs.innerHTML = '';

        combos.forEach((combo, i) => {
            const label = combo.join(' / ');

            const map = {};
            options.forEach((opt, idx) => map[opt.name] = combo[idx]);

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${label}</strong></td>
                <td>
                    <div class="variant-image-cell d-flex align-items-center gap-2">
                        <img class="variant-image-preview d-none" src="" alt="Variant"
                            style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                        <span class="variant-image-placeholder text-muted">No image</span>
                        <input type="file" class="form-control form-control-sm variant-image-input" name="variants[${i}][image]" accept="image/*">
                    </div>
                </td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][sku]" placeholder="SKU (optional)"></td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][serial_start]" placeholder="Serial start"></td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][serial_end]" placeholder="Serial end"></td>
                <td><input type="text" class="form-control form-control-sm" name="variants[${i}][price]" placeholder="Price (optional)"></td>
                <td><input type="number" class="form-control form-control-sm" name="variants[${i}][stock]" min="0" value="0" required></td>
                <td>
                    <select class="form-select form-select-sm" name="variants[${i}][status]">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </td>
            `;
            variantsTbody.appendChild(tr);

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = `variants[${i}][map]`;
            hidden.value = JSON.stringify(map);
            variantsHiddenInputs.appendChild(hidden);
        });

        variantsGridWrap.classList.remove('d-none');
        clearVariantsBtn.classList.remove('d-none');
    });

    clearVariantsBtn.addEventListener('click', () => {
        variantsTbody.innerHTML = '';
        variantsHiddenInputs.innerHTML = '';
        variantsGridWrap.classList.add('d-none');
        clearVariantsBtn.classList.add('d-none');
    });
})();
