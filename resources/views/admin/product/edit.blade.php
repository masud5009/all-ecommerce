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

                <div class="row">
                    <div class="col-10">
                        <x-slider-image noteText="Recommended image size: 800x800" label="Gallery Images"
                            :sliders="$product->sliderImage" />
                    </div>

                    <!-- featured image -->
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label class="">{{ __('Thumbnail Image') }} <span class="text-danger">**</span></label>
                            <br>
                            <div class="thumb-preview mb-3">
                                <img src="{{ $product->thumbnail ? asset('assets/img/product/' . $product->thumbnail) : asset('assets/admin/noimage.jpg') }}"
                                    alt="..." class="uploaded-img">
                            </div>
                        </div>
                    </div>
                </div>

                <form id="blogForm" action="{{ route('admin.product.update', ['id' => $product->id]) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @php
                        $productType = strtolower((string) $product->type);
                        $displayType = $productType ? ucfirst($productType) : $product->type;
                    @endphp
                    <input type="file" class="img-input" name="thumbnail" id="thumbnailInput">
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="row g-3">
                                <div id="sliders"></div>


                                @if ($productType === 'physical')
                                    <x-text-input col="4" placeholder="Enter stock" name="stock" type="text"
                                        label="Stock" required="*" value="{{ $product->stock }}" />
                                @endif

                                <x-text-input col="4" placeholder="Enter current price" name="current_price"
                                    type="text" label="Current Price" required="*"
                                    value="{{ $product->current_price }}" />
                                <x-text-input col="4" placeholder="Enter previous price" name="previous_price"
                                    type="text" label="Previous Price" value="{{ $product->previous_price }}" />

                                <x-text-input col="4" placeholder="Enter product sku" name="sku" type="text"
                                    label="SKU" required="*" value="{{ $product->sku }}" />

                                @php
                                    $options = ['1' => 'Show', '0' => 'Hide'];
                                @endphp
                                <x-text-input col="4" placeholder="Select a Status" name="status"
                                    type="custom-select" label="Status" required="*" :dataInfo="$options"
                                    value="{{ $product->status }}" />

                                <x-text-input col="4" name="type" type="text" label="Type" required="*"
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
                                    <div class="mt-2 d-none variants-grid" id="variantsGridWrap">

                                        <div class="table-responsive">
                                            <table class="table table-bordered variants-table">
                                                <thead>
                                                    <tr>
                                                        <th style="min-width: 220px">{{ __('Variant') }}</th>
                                                        <th style="min-width: 200px">{{ __('Image') }}</th>
                                                        <th style="min-width: 160px">{{ __('SKU') }}</th>
                                                        <th style="min-width: 140px">{{ __('Serial Start') }}</th>
                                                        <th style="min-width: 140px">{{ __('Serial End') }}</th>
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
                    </div>

                    <div class="form-group language-card">
                        <div class="row language-div">
                            @include('admin.include.languages', ['languages' => $languages])

                            @foreach ($languages as $lang)
                                @php
                                    $code = $lang->code;
                                    $content = $lang->content;
                                    $metaKeywordValue = '';

                                    if (!empty($content?->meta_keywords)) {
                                        $decodedMetaKeywords = json_decode($content->meta_keywords, true);

                                        if (is_array($decodedMetaKeywords)) {
                                            $metaKeywordValue = implode(',', array_values(array_filter(array_map('trim', $decodedMetaKeywords))));
                                        } else {
                                            $metaKeywordValue = $content->meta_keywords;
                                        }
                                    }
                                @endphp
                                <div class="language-content {{ $lang->id == $defaultLang->id || $lang->id == @$content->language_id ? '' : 'd-none' }}"
                                    id="language_{{ $lang->id }}">

                                    <x-text-input col="12" placeholder="Enter product title"
                                        name="{{ $lang->code }}_title" type="text" label="Title" required="*"
                                        language="{{ $lang->code }}" value="{{ @$content->title }}" />

                                    <x-text-input col="12" placeholder="Select a Category"
                                        name="{{ $lang->code }}_category_id" type="select" label="Category"
                                        required="*" language="{{ $lang->code }}" :dataInfo="$lang->categories"
                                        value="{{ @$content->category_id }}" />

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{ __('Subcategory') }}</label>
                                            <select name="{{ $lang->code }}_subcategory_id"
                                                class="form-select subcategory-select err_{{ $lang->code }}_subcategory_id"
                                                data-language-code="{{ $lang->code }}">
                                                <option value="">{{ __('Select a Subcategory') }}</option>
                                                @foreach ($lang->subcategories as $subcategory)
                                                    <option value="{{ $subcategory->id }}"
                                                        data-category-id="{{ $subcategory->category_id }}"
                                                        @selected($subcategory->id == @$content->subcategory_id)>
                                                        {{ $subcategory->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <p id="err_{{ $lang->code }}_subcategory_id" class="text-danger em"></p>
                                        </div>
                                    </div>

                                    <x-text-input col="12" placeholder="Enter summary text"
                                        name="{{ $lang->code }}_summary" type="textarea" label="Summary"
                                        language="{{ $lang->code }}" value="{!! @$content->summary !!}" />

                                    <x-text-input col="12" placeholder="Enter description"
                                        name="{{ $lang->code }}_description" type="editor" label="Description"
                                        required="*" language="{{ $lang->code }}" value="{!! @$content->description !!}" />

                                    <x-text-input col="12" placeholder="Enter meta keywords"
                                        name="{{ $lang->code }}_meta_keyword" type="tagsinput" label="Meta Keywords"
                                        language="{{ $lang->code }}" value="{{ $metaKeywordValue }}" />

                                    <x-text-input col="12" placeholder="Enter meta description"
                                        name="{{ $lang->code }}_meta_description" type="textarea"
                                        label="Meta Description" language="{{ $lang->code }}"
                                        value="{!! @$content->meta_description !!}" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button class="btn btn-success" id="blogSubmit" type="button">{{ __('Update') }}</button>
                    </div>
                </form>

                @if ($product->has_variants && $product->variants->isNotEmpty())
                    <hr>
                    <div class="mt-3">
                        <h6 class="mb-2">{{ __('Variant Details') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>{{ __('Variant') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('SKU') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Track Serial') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->variants as $variant)
                                        @php
                                            $variantParts = $variant->variantValues
                                                ->sortBy(function ($variantValue) {
                                                    return optional(optional($variantValue->optionValue)->option)->position ?? 0;
                                                })
                                                ->map(function ($variantValue) {
                                                    $option = optional($variantValue->optionValue)->option;
                                                    $value = optional($variantValue->optionValue)->value;

                                                    if (!$option || $value === null) {
                                                        return null;
                                                    }

                                                    return $option->name . ': ' . $value;
                                                })
                                                ->filter()
                                                ->values();

                                            $variantLabel = $variantParts->isNotEmpty()
                                                ? $variantParts->implode(', ')
                                                : __('Default');
                                        @endphp
                                        <tr>
                                            <td>{{ $variantLabel }}</td>
                                            <td>
                                                @if ($variant->image)
                                                    <img src="{{ asset('assets/img/product/variant/' . $variant->image) }}"
                                                        alt="Variant" style="width:40px;height:40px;object-fit:cover;border-radius:4px;">
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $variant->sku ?? 'N/A' }}</td>
                                            <td>
                                                @if ((int) $variant->status === 1)
                                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>{{ (int) $variant->track_serial === 1 ? __('Yes') : __('No') }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.product.variant.details', ['id' => $variant->id]) }}"
                                                        class="btn btn-sm edit-button">
                                                        <span class="fas fa-eye"></span>
                                                    </a>
                                                    <a href="{{ route('admin.product.variant.restock', ['variant_id' => $variant->id]) }}"
                                                        class="btn btn-sm edit-button">
                                                        <span class="fas fa-plus"></span>
                                                    </a>
                                                    <form action="{{ route('admin.product.variant.delete') }}" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('{{ __('Are you sure you want to remove this variant?') }}');">
                                                        @csrf
                                                        <input type="hidden" name="variant_id"
                                                            value="{{ $variant->id }}">
                                                        <button type="submit" class="btn btn-sm text-danger">
                                                            <span class="fas fa-trash-alt"></span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@section('script')
    <div id="productPageConfig" data-upload-slider-image="{{ route('admin.product.slider') }}"
        data-rmv-slider-image="{{ route('admin.product.slider-remove') }}"
        data-rmv-db-slider-image="{{ route('admin.product.db-slider-remove') }}"
        data-existing-options='@json($variantOptions ?? [])' data-existing-variants='@json($variantsData ?? [])'></div>
    <script src="{{ asset('assets/admin/js/product.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dropzone-slider.js') }}"></script>
    <script src="{{ asset('assets/admin/js/blog.js') }}"></script>
@endsection
@endsection


