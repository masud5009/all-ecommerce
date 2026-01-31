@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Product Management') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.product', ['language' => $defaultLang->code]) }}">{{ __('Products') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Edit Product') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Edit Product') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.product', ['language' => $defaultLang->code]) }}"
                            class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-danger alert-dismissible pb-1 d-none" id="blog_errors">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <ul></ul>
                </div>

                <x-slider-image noteText="Recommended image size: 800x800" label="Gallery Images" :sliders="$product->sliderImage" />

                <form id="blogForm" action="{{ route('admin.product.update', ['id' => $product->id]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @php
                        $productType = strtolower((string) $product->type);
                        $displayType = $productType ? ucfirst($productType) : $product->type;
                    @endphp

                    <div class="row mb-4">
                        <div class="col-lg-9">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div id="sliders"></div>
                                </div>

                                @if ($productType === 'physical')
                                    <x-text-input col="6" placeholder="Enter stock" name="stock" type="text"
                                        label="Stock" required="*" value="{{ $product->stock }}" />
                                @endif

                                <x-text-input col="6" placeholder="Enter current price" name="current_price"
                                    type="text" label="Current Price" required="*"
                                    value="{{ $product->current_price }}" />
                                <x-text-input col="6" placeholder="Enter previous price" name="previous_price"
                                    type="text" label="Previous Price" value="{{ $product->previous_price }}" />

                                <x-text-input col="6" placeholder="Enter product sku" name="sku" type="text"
                                    label="SKU" required="*" value="{{ $product->sku }}" />

                                @php
                                    $options = ['1' => 'Show', '0' => 'Hide'];
                                @endphp
                                <x-text-input col="6" placeholder="Select a Status" name="status"
                                    type="custom-select" label="Status" required="*" :dataInfo="$options"
                                    value="{{ $product->status }}" />

                                <x-text-input col="6" name="type" type="text" label="Type" required="*"
                                    attribute="readonly" value="{{ $displayType }}" />

                                {{-- File Type --}}
                                @if ($productType === 'digital')
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Type') . ' **' }} </label>
                                            <select class="form-select" id="fileType" name="file_type">
                                                <option value="upload" @selected($product->file_type == 'upload')>
                                                    {{ __('File Upload') }}</option>
                                                <option value="link" @selected($product->file_type == 'link')>
                                                    {{ __('File Download Link') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div id="downloadFile"
                                            class="form-group {{ $product->file_type == 'link' ? 'd-none' : '' }}">
                                            <label for="">{{ __('Downloadable File') . ' **' }} </label>
                                            <input type="file" class="form-control" id="customFile"
                                                name="download_file" />
                                            <p class="mb-0 text-warning">{{ __('Only zip file is allowed') }}</p>
                                        </div>

                                        <div id="downloadLink"
                                            class="form-group {{ $product->file_type == 'upload' ? 'd-none' : '' }}">
                                            <label>{{ __('Downloadable Link') . ' **' }} </label>
                                            <input name="download_link" type="text" class="form-control"
                                                value="{{ $product->download_link }}">
                                            <p id="errdownload_link" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-6">
                                    <div class="form-group">
                                        <div>
                                            <label class="cursor-pointer">
                                                <input type="checkbox" id="has_variants" name="has_variants"
                                                    value="1" @checked($product->has_variants)>
                                                {{ __('Has Variants') }}
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            {{ __('If enabled, stock/price will be handled per-variant.') }}
                                        </small>
                                    </div>
                                </div>


                                <div class="col-12 {{ $product->has_variants ? '' : 'd-none' }} border"
                                    id="variationsWrap">
                                    <div class="col-lg-12">
                                        <div class="form-group d-flex justify-content-between align-item-center">
                                            <h6 class="pb-1">{{ __('Variations') }}</h6>
                                            <button type="button" class="btn btn-sm btn-primary float-left"
                                                id="addOptionBtn">
                                                + {{ __('Add Option') }}
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Options list --}}
                                    <div id="optionsList"></div>

                                    <!-- generate button -->
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="d-flex gap-2 flex-wrap">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="generateVariantsBtn">
                                                    {{ __('Generate Variants') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-danger d-none"
                                                    id="clearVariantsBtn">
                                                    {{ __('Clear Variants') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>



                                    {{-- Variants grid --}}
                                    <div class="mt-2 d-none" id="variantsGridWrap">

                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width: 220px">{{ __('Variant') }}</th>
                                                        <th style="min-width: 160px">{{ __('SKU') }}</th>
                                                        <th style="min-width: 120px">{{ __('Price') }}</th>
                                                        <th style="min-width: 120px">{{ __('Stock') }}</th>
                                                        <th style="min-width: 120px">{{ __('Status') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="variantsTbody"></tbody>
                                            </table>
                                        </div>

                                        <!-- Hidden inputs will be appended here-->
                                        <div id="variantsHiddenInputs"></div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- featured image -->
                        <div class="col-lg-3">
                            <div class="card upload-card h-100">
                                <div class="card-body">
                                    <div class="form-group text-center">
                                        <label class="d-block mb-2">{{ __('Thumbnail') }}*</label>
                                        <div class="thumb-preview mb-3">
                                            <img src="{{ $product->thumbnail ? asset('assets/img/product/' . $product->thumbnail) : asset('assets/admin/noimage.jpg') }}"
                                                alt="..." class="uploaded-img">
                                        </div>
                                        <input type="file" class="img-input" name="thumbnail" id="thumbnailInput">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm language-card">
                        <div class="card-body">
                            <div class="row language-div">
                                @include('admin.include.languages', ['languages' => $languages])

                                @foreach ($languages as $lang)
                                    @php
                                        $code = $lang->code;
                                        $content = $lang->content;
                                    @endphp
                                    <div class="row language-content {{ $lang->id == $defaultLang->id || $lang->id == @$content->language_id ? '' : 'd-none' }}"
                                        id="language_{{ $lang->id }}">

                                        <x-text-input col="12" placeholder="Enter product title"
                                            name="{{ $lang->code }}_title" type="text" label="Title"
                                            required="*" language="{{ $lang->code }}"
                                            value="{{ @$content->title }}" />

                                        <x-text-input col="12" placeholder="Select a Category"
                                            name="{{ $lang->code }}_category_id" type="select" label="Category"
                                            required="*" language="{{ $lang->code }}" :dataInfo="$lang->categories"
                                            value="{{ @$content->category_id }}" />

                                        <x-text-input col="12" placeholder="Enter summary text"
                                            name="{{ $lang->code }}_summary" type="textarea" label="Summary"
                                            language="{{ $lang->code }}" value="{!! @$content->summary !!}" />

                                        <x-text-input col="12" placeholder="Enter description"
                                            name="{{ $lang->code }}_description" type="editor" label="Text"
                                            required="*" language="{{ $lang->code }}"
                                            value="{!! @$content->description !!}" />

                                        <x-text-input col="12" placeholder="Enter meta keyword"
                                            name="{{ $lang->code }}_meta_keyword" type="tagsinput"
                                            label="Meta Keyword" language="{{ $lang->code }}"
                                            value="{{ @$content->meta_keyword }}" />

                                        <x-text-input col="12" placeholder="Enter meta description"
                                            name="{{ $lang->code }}_meta_description" type="textarea"
                                            label="Meta Description" language="{{ $lang->code }}"
                                            value="{!! @$content->meta_description !!}" />
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-success" id="blogSubmit" type="button">{{ __('Update') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@section('script')
    <script>
        const uploadSliderImage = "{{ route('admin.product.slider') }}";
        const rmvSliderImage = "{{ route('admin.product.slider-remove') }}";
        const rmvDbSliderImage = "{{ route('admin.product.db-slider-remove') }}";
    </script>
    <script>
        const existingOptions = @json($variantOptions ?? []);
        const existingVariants = @json($variantsData ?? []);

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
            }, [
                []
            ]);
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

        let optionIndex = 0;

        function toggleBaseFields(disabled) {
            if (stockInput) stockInput.disabled = disabled;
            if (priceInput) {
                priceInput.readOnly = disabled;
                priceInput.classList.toggle('bg-light', disabled);
            }
            if (skuInput) {
                skuInput.readOnly = disabled;
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
            variationsWrap.classList.toggle('d-none', !show);
            if (!show) resetVariations();
        }

        if (hasVariantsEl) {
            hasVariantsEl.addEventListener('change', function() {
                const on = !!this.checked;
                toggleBaseFields(on);
                showVariations(on);

                if (on && optionsList.querySelectorAll('.option-row').length === 0) {
                    addOptionRow();
                }
            });
        }

        function addOptionRow(name = '', values = '') {
            const wrapper = document.createElement('div');
            wrapper.className = 'row align-items-end g-2 mb-2 option-row';
            wrapper.dataset.index = optionIndex;

            wrapper.innerHTML = `
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Option Name</label>
                        <input type="text" class="form-control"
                            name="variant_options[${optionIndex}][name]"
                            placeholder="e.g. Size" value="${name}">
                        <small class="text-muted d-block invisible">Spacer</small>
                    </div>
                </div>
                <div class="col-lg-8">
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

            wrapper.querySelector('.remove-option').addEventListener('click', () => {
                wrapper.remove();
            });

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
                return {
                    name,
                    values
                };
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
                    <td><input type="text" class="form-control" name="variants[${i}][sku]" placeholder="SKU (optional)"></td>
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
    </script>
    <script src="{{ asset('assets/admin/js/dropzone-slider.js') }}"></script>
    <script src="{{ asset('assets/admin/js/blog.js') }}"></script>
@endsection
@endsection
