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
                <a href="#">{{ __('Create Variation') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12 mb-5">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('Create Variation') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <a href="{{ route('admin.product',['language'=>request()->input('language')]) }}" class="btn btn-primary btn-sm float-lg-end float-left">
                            <i class="fas fa-angle-double-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-10 m-auto">
                    <div class="alert alert-danger alert-dismissible pb-1 d-none" id="blog_errors">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <ul></ul>
                    </div>
                    <form id="blogForm" action="{{ route('admin.product.variant_store') }}" method="post">
                        @csrf
                        <input type="hidden" value="{{ $productId }}" name="product_id">

                        <div class="js-repeater">
                            <div class="mb-3 text-center">
                                <button class="btn btn-primary js-repeater-add" type="button"><i class="fas fa-plus"></i>
                                    {{ __('Add Varient') }}</button>
                            </div>
                            <div id="js-repeater-container">
                                @foreach ($variations as $key => $lwVariaion)
                                    <div class="js-repeater-item" data-item="{{ $key }}">
                                        <div class="mb-3 row align-items-end">
                                            @for ($i = 0; $i < count($languages); $i++)
                                                <div class="col-md-4 mb-3">
                                                    <label for="form" class="form-label ">{{ __('Variation Name') }}
                                                        ({{ $languages[$i]->code }})
                                                    </label>
                                                    <input
                                                        value="{{ old($languages[$i]->code . '_variation_' . $key, $lwVariaion[$i]['variant_name'] ?? '') }}"
                                                        type="text" class="form-control" placeholder=""
                                                        name="{{ $languages[$i]->code }}_variation_{{ $key }}">
                                                    <input type="hidden" value="{{ $key }}"
                                                        name="variation_helper[]">
                                                </div>
                                            @endfor
                                            <div class="col-md-4 mb-3 varient-btn-div">
                                                <button class="btn btn-danger btn-sm js-repeater-remove mb-1 mr-2"
                                                    type="button" onclick="$(this).parents('.js-repeater-item').remove()">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                                <button class="btn btn-success btn-sm js-repeater-child-add mb-1"
                                                    type="button" data-it="{{ $key }}"><i
                                                        class="fas fa-plus"></i>{{ __('Add Option') }}
                                                </button>
                                            </div>

                                            <div class="repeater-child-list mt-2 col-md-12"
                                                id="options{{ $key }}">
                                                @php
                                                    $op = json_decode($lwVariaion[0]['option_name']);
                                                    $op_price = json_decode($lwVariaion[0]['option_price']);
                                                    $op_stock = json_decode($lwVariaion[0]['option_stock']);
                                                @endphp
                                                @if ($op)
                                                    @foreach ($op as $opIn => $w)
                                                        <div class="repeater-child-item mb-3"
                                                            id="options{{ $key }}">
                                                            <div class="row align-items-start">
                                                                @php
                                                                    $opArr = [];
                                                                    for ($i = 0; $i < count($languages); $i++) {
                                                                        $opArr[$i] = json_decode(
                                                                            $lwVariaion[$i]['option_name'] ?? '',
                                                                        );
                                                                    }
                                                                @endphp
                                                                @for ($i = 0; $i < count($languages); $i++)
                                                                    <div class="col-md-3">
                                                                        <label for="form"
                                                                            class="form-label ">{{ __('Option Name') }}
                                                                            (In {{ $languages[$i]->code }})
                                                                        </label>
                                                                        <input
                                                                            name="{{ $languages[$i]->code }}_options1_{{ $key }}[]"
                                                                            type="text" class="form-control"
                                                                            value="{{ old($languages[$i]->code . '_options1_' . $key . '.' . $opIn, $opArr[$i][$opIn] ?? '') }}"
                                                                            placeholder="">
                                                                    </div>
                                                                @endfor
                                                                <div class="col-md-2">
                                                                    <label for="form"
                                                                        class="form-label ">{{ __('Price') }}
                                                                        ({{ $websiteInfo->currency_symbol }})</label>
                                                                    <input name="options2_{{ $key }}[]"
                                                                        type="number" class="form-control"
                                                                        value="{{ old('options2_' . $key . '.' . $opIn, $op_price[$opIn]) }}"
                                                                        placeholder="0">
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <label for="form"
                                                                        class="form-label">{{ __('Stock') }}</label>
                                                                    <input name="options3_{{ $key }}[]"
                                                                        type="number" class="form-control"
                                                                        value="{{ old('options3_' . $key . '.' . $opIn, $op_stock[$opIn]) }}"
                                                                        placeholder="0">
                                                                </div>
                                                                <div class="col-md-2 option-remove-btn">
                                                                    <button
                                                                        class="btn btn-danger js-repeater-child-remove btn-sm"
                                                                        type="button"
                                                                        onclick="$(this).parents('.repeater-child-item').remove()"><i
                                                                            class="fas fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" id="blogSubmit" class="btn btn-success btn-md">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/blog.js') }}"></script>
    <script>
        $(document).ready(function() {
            const languages = <?= json_encode($languages) ?>;
            const currencySymbol = "{{ $websiteInfo->currency_symbol }}";

            // Add Variation
            function addVariation(key) {
                let variationHTML =
                    `<div class="js-repeater-item" data-item="${key}"><div class="mb-3 row align-items-end">`;

                // Generate input fields for all languages
                languages.forEach(language => {
                    variationHTML += `
                <div class="col-md-4 mb-3">
                    <label class="form-label">Variation Name (In ${language.code})</label>
                    <input type="text" class="form-control" name="${language.code}_variation_${key}" />
                    <input type="hidden" name="variation_helper[]" value="${key}" />
                </div>`;
                });

                variationHTML += `
            <div class="col-md-4 mb-3 varient-btn-div">
                <button class="btn btn-danger btn-sm js-repeater-remove mb-1 mr-2" type="button">
                    <i class="fas fa-times"></i>
                </button>
                <button class="btn btn-success btn-sm js-repeater-child-add mb-1" type="button" data-it="${key}">
                    <i class="fas fa-plus"></i> Add Option
                </button>
                </div>
                <div class="repeater-child-list mt-2 col-md-12" id="options${key}"></div>`;
                $("#js-repeater-container").append(variationHTML);
            }

            // Add Option
            function addOption(parentKey, optionIndex) {
                let optionHTML = `<div class="repeater-child-item mb-3" id="options${parentKey}_${optionIndex}">
            <div class="row align-items-start">`;

                // Generate input fields for all languages
                languages.forEach(language => {
                    optionHTML += `
                <div class="col-md-3">
                    <label class="form-label">Option Name (${language.code})</label>
                    <input type="text" class="form-control" name="${language.code}_options1_${parentKey}[]" />
                </div>`;
                });

                optionHTML += `
            <div class="col-md-2">
                <label class="form-label">Price (${currencySymbol})</label>
                <input type="number" class="form-control" name="options2_${parentKey}[]" placeholder="0" />
            </div>
            <div class="col-md-2">
                <label class="form-label">Stock</label>
                <input type="number" class="form-control" name="options3_${parentKey}[]" placeholder="0" />
            </div>
            <div class="col-md-2 option-remove-btn">
                <button class="btn btn-danger js-repeater-child-remove btn-sm" type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>`;

                $(`#options${parentKey}`).append(optionHTML);
            }

            // Initialize event listeners
            let variationKey = $(".js-repeater-item").length || 0;

            $(".js-repeater-add").click(() => {
                addVariation(variationKey++);
            });

            $(document).on("click", ".js-repeater-child-add", function() {
                const parentKey = $(this).data("it");
                const optionIndex = $(`#options${parentKey} .repeater-child-item`).length;
                addOption(parentKey, optionIndex);
            });

            $(document).on("click", ".js-repeater-remove", function() {
                $(this).closest(".js-repeater-item").remove();
            });

            $(document).on("click", ".js-repeater-child-remove", function() {
                $(this).closest(".repeater-child-item").remove();
            });
        });
    </script>
@endsection
