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
                <a href="#">{{ __('Create Product') }}</a>
            </li>
        </ol>
    </nav>


    <div class="col-lg-12 mb-5">
        <div class="alert alert-danger alert-dismissible pb-1 d-none" id="blog_errors">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <ul></ul>
        </div>

        <!--slider images-->
        <x-slider-image noteText="Recomanded Image size : 800x800" label="Gallery Images" />

        <form id="blogForm" action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <!--prodct info-->
                <div class="col-lg-9">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div id="sliders"></div>

                                @if (request()->type == 'physical')
                                    <x-text-input col="6" placeholder="Enter stock" name="stock" type="text"
                                        label="Stock" required="*" />
                                @endif

                                <x-text-input col="6" placeholder="Enter current price" name="current_price"
                                    type="text" label="Current Price" required="*" />

                                <x-text-input col="6" placeholder="Enter previous price" name="previous_price"
                                    type="text" label="Previous Price" />

                                <x-text-input col="6" placeholder="Enter product sku" name="sku" type="text"
                                    label="SKU" required="*" />

                                @php
                                    $options = ['1' => 'Show', '0' => 'Hide'];
                                @endphp
                                <x-text-input col="6" placeholder="Select a Status" name="status"
                                    type="custom-select" label="Status" required="*" :dataInfo="$options" />

                                <x-text-input value="{{ ucfirst(request()->input('type')) }}" col="6" name="type"
                                    type="text" label="Type" required="*" attribute="readonly" />

                                {{-- File Type --}}
                                @if (request()->type == 'digital')
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ __('Type') . ' **' }} </label>
                                            <select class="form-select" id="fileType" name="file_type">
                                                <option value="upload" selected>{{ __('File Upload') }}</option>
                                                <option value="link">{{ __('File Download Link') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div id="downloadFile" class="form-group">
                                            <label for="">{{ __('Downloadable File') . ' **' }} </label>
                                            <input type="file" class="form-control" id="customFile"
                                                name="download_file" />
                                            <p class="mb-0 text-warning">{{ __('Only zip file is allowed') }}</p>
                                        </div>

                                        <div id="downloadLink" class="form-group d-none">
                                            <label>{{ __('Downloadable Link') . ' **' }} </label>
                                            <input name="download_link" type="text" value=""
                                                class="form-control">
                                            <p id="errdownload_link" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                @endif

                                {{-- ✅ HAS VARIANTS TOGGLE --}}
                                <div class="col-lg-12">
                                    <div class="form-group mt-2">
                                        <label class="mb-1">{{ __('Has Variants?') }}</label>
                                        <div>
                                            <label class="cursor-pointer">
                                                <input type="checkbox" id="has_variants" name="has_variants"
                                                    value="1">
                                                {{ __('This product has variants') }}
                                            </label>
                                        </div>
                                        <small class="text-muted">
                                            {{ __('If enabled, stock/price will be handled per-variant.') }}
                                        </small>
                                    </div>
                                </div>

                                {{-- ✅ VARIATIONS UI --}}
                                <div class="col-lg-12 d-none" id="variationsWrap">
                                    <div class="card mt-3">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ __('Variations') }}</h6>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                id="addOptionBtn">
                                                + {{ __('Add Option') }}
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            {{-- Options list --}}
                                            <div id="optionsList"></div>

                                            <div class="mt-3 d-flex gap-2">
                                                <button type="button" class="btn btn-outline-primary"
                                                    id="generateVariantsBtn">
                                                    {{ __('Generate Variants') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-danger d-none"
                                                    id="clearVariantsBtn">
                                                    {{ __('Clear Variants') }}
                                                </button>
                                            </div>

                                            {{-- Variants grid --}}
                                            <div class="mt-4 d-none" id="variantsGridWrap">
                                                <h6 class="mb-2">{{ __('Variants') }}</h6>

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

                                                {{-- Hidden inputs will be appended here --}}
                                                <div id="variantsHiddenInputs"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- ✅ END VARIATIONS UI --}}

                            </div>
                        </div>
                    </div>
                </div>

                <!-- featuerd image -->
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Thumbnail*</label>
                                    <br>
                                    <div class="thumb-preview">
                                        <img src="{{ asset('assets/admin/noimage.jpg') }}" alt="..."
                                            class="uploaded-img">
                                    </div>
                                    <input type="file" class="img-input" name="thumbnail" id="thumbnailInput">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--prduction info language dependency-->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-lg-12 mx-auto">
                                <div class="row language-div">
                                    @include('admin.include.languages')

                                    @foreach ($languages as $lang)
                                        @php
                                            $code = $lang->code;
                                            $blogInfo = App\Models\BlogContent::where([['language_id', $lang->id]])->first();
                                        @endphp

                                        <div class="row language-content {{ $lang->id == $defaultLang->id ? '' : 'd-none' }}"
                                            id="language_{{ $lang->id }}">

                                            <x-text-input col="12" placeholder="Enter product title"
                                                name="{{ $lang->code }}_title" type="text" label="Title"
                                                required="*" language="{{ $lang->code }}" />

                                            <x-text-input col="12" placeholder="Select a Category"
                                                name="{{ $lang->code }}_category_id" type="select"
                                                label="Category" required="*" language="{{ $lang->code }}"
                                                :dataInfo="$lang->categories" />

                                            <x-text-input col="12" placeholder="Enter summary text"
                                                name="{{ $lang->code }}_summary" type="textarea"
                                                label="Summary" language="{{ $lang->code }}" />

                                            <x-text-input col="12" placeholder="Enter description"
                                                name="{{ $lang->code }}_description" type="editor"
                                                label="Text" required="*" language="{{ $lang->code }}" />

                                            <x-text-input col="12" placeholder="Enter meta keyword"
                                                name="{{ $lang->code }}_meta_keyword" type="tagsinput"
                                                label="Meta Keyword" language="{{ $lang->code }}" />

                                            <x-text-input col="12" placeholder="Enter meta description"
                                                name="{{ $lang->code }}_meta_description" type="textarea"
                                                label="Meta Description" language="{{ $lang->code }}" />
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-success" id="blogSubmit" type="button">Submit</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@section('script')
    <script>
        const uploadSliderImage = "{{ route('admin.product.slider') }}";
        const rmvSliderImage = "{{ route('admin.product.slider-remove') }}";
        const rmvDbSliderImage = "{{ route('admin.product.db-slider-remove') }}";
    </script>

    <script>
        // ---- helpers ----
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

        // ---- Elements ----
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
            if (priceInput) priceInput.disabled = disabled;
            if (skuInput) skuInput.disabled = disabled;
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

                // auto add 1 row when enabled (optional)
                if (on && optionsList.querySelectorAll('.option-row').length === 0) {
                    addOptionRow();
                }
            });
        }

        function addOptionRow(name = '', values = '') {
            const wrapper = document.createElement('div');
            wrapper.className = 'row align-items-end mb-2 option-row';
            wrapper.dataset.index = optionIndex;

            wrapper.innerHTML = `
                <div class="col-lg-4">
                    <div class="form-group">
                        <label>Option Name</label>
                        <input type="text" class="form-control"
                            name="variant_options[${optionIndex}][name]"
                            placeholder="e.g. Size" value="${name}">
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="form-group">
                        <label>Values</label>
                        <input type="text" class="form-control"
                            name="variant_options[${optionIndex}][values]"
                            placeholder="e.g. S, M, L" value="${values}">
                        <small class="text-muted">Comma separated values</small>
                    </div>
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-danger btn-sm w-100 remove-option">×</button>
                </div>
            `;

            wrapper.querySelector('.remove-option').addEventListener('click', () => {
                wrapper.remove();
            });

            optionsList.appendChild(wrapper);
            optionIndex++;
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
