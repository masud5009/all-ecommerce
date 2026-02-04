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

    function buildSkuSequence(startRaw, endRaw, count) {
        const startStr = (startRaw || '').trim();
        const endStr = (endRaw || '').trim();

        if (!startStr && !endStr) return { list: null };
        if (!startStr || !endStr) return { error: 'Please provide both SKU start and SKU end.' };
        if (!/^\d+$/.test(startStr) || !/^\d+$/.test(endStr)) return { error: 'SKU range must be numeric.' };

        const startNum = BigInt(startStr);
        const endNum = BigInt(endStr);

        if (endNum < startNum) return { error: 'SKU end must be greater than or equal to start.' };

        const needed = BigInt(count);
        const rangeSize = endNum - startNum + 1n;
        if (rangeSize < needed) return { error: 'SKU range is smaller than the number of variants.' };

        const width = Math.max(startStr.length, endStr.length);
        const list = [];

        for (let i = 0n; i < needed; i++) {
            const sku = (startNum + i).toString().padStart(width, '0');
            list.push(sku);
        }

        return { list };
    }

    const hasVariantsEl = document.getElementById('has_variants');
    const variationsWrap = document.getElementById('variationsWrap');

    const stockInput = document.querySelector('[name="stock"]');
    const priceInput = document.querySelector('[name="current_price"]');
    const skuInput = document.querySelector('[name="sku"]');
    const variantSkuStartInput = document.getElementById('variantSkuStart');
    const variantSkuEndInput = document.getElementById('variantSkuEnd');

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
            const price = variant.price ?? '';
            const stock = variant.stock ?? 0;
            const status = typeof variant.status === 'undefined' ? 1 : variant.status;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${label}</strong></td>
                <td><input type="text" class="form-control" name="variants[${i}][sku]" value="${sku}" placeholder="SKU (optional)"></td>
                <td><input type="text" class="form-control" name="variants[${i}][price]" value="${price}" placeholder="Price (optional)"></td>
                <td><input type="number" class="form-control" name="variants[${i}][stock]" min="0" value="${stock}" required></td>
                <td>
                    <select class="form-select" name="variants[${i}][status]">
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

        let skuSequence = null;
        const skuSequenceResult = buildSkuSequence(
            variantSkuStartInput ? variantSkuStartInput.value : '',
            variantSkuEndInput ? variantSkuEndInput.value : '',
            combos.length
        );
        if (skuSequenceResult && skuSequenceResult.error) {
            alert(skuSequenceResult.error);
        } else if (skuSequenceResult && skuSequenceResult.list) {
            skuSequence = skuSequenceResult.list;
        }

        variantsTbody.innerHTML = '';
        variantsHiddenInputs.innerHTML = '';

        combos.forEach((combo, i) => {
            const label = combo.join(' / ');

            const map = {};
            options.forEach((opt, idx) => map[opt.name] = combo[idx]);

            const skuValue = skuSequence ? skuSequence[i] : '';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><strong>${label}</strong></td>
                <td><input type="text" class="form-control" name="variants[${i}][sku]" value="${skuValue}" placeholder="SKU (optional)"></td>
                <td><input type="text" class="form-control" name="variants[${i}][price]" placeholder="Price (optional)"></td>
                <td><input type="number" class="form-control" name="variants[${i}][stock]" min="0" value="0" required></td>
                <td>
                    <select class="form-select" name="variants[${i}][status]">
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
