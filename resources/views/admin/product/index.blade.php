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
                <a href="#">{{ __('Products') }}</a>
            </li>
        </ol>
    </nav>

    @php
        $hasProductFilter =
            request()->filled('search') ||
            request()->filled('status') ||
            request()->filled('stock') ||
            request()->filled('variant_type') ||
            request()->filled('product_type');
    @endphp

    <div class="col-lg-12">
        <div class="card">
            <x-bulk-delete :url="route('admin.product.bulk_delete')" itemTextName="products" />
            <div class="card-header">
                <div class="row align-items-center g-2">
                    <div class="col-lg-3 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Products') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6">
                        @include('admin.partials.languages')
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="info-header-content d-flex flex-wrap gap-2 justify-content-lg-end justify-content-start align-items-center">
                            <button class="btn btn-danger btn-sm  d-none bulk-delete"
                                data-href="{{ route('admin.product.bulk_delete') }}">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                            </button>

                            <button type="button"
                                class="btn btn-sm me-2 {{ $hasProductFilter ? 'btn-primary' : 'btn-outline-info' }}"
                                data-bs-toggle="offcanvas" data-bs-target="#productFilterOffcanvas"
                                aria-controls="productFilterOffcanvas">
                                <i class="fas fa-filter"></i> {{ __('Filter') }}
                            </button>

                            @if ($hasProductFilter)
                                <a href="{{ route('admin.product', ['language' => request('language', app('defaultLang')->code)]) }}"
                                    class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="fas fa-times"></i> {{ __('Clear Filter') }}
                                </a>
                            @endif

                            <a href="{{ route('admin.product.variant.restock') }}"
                                class="btn btn-outline-primary btn-sm me-2">
                                <i class="fas fa-boxes"></i> {{ __('Restock Variant') }}
                            </a>

                            <a href="{{ route('admin.product.import_form') }}" class="btn btn-success btn-sm me-2">
                                <i class="fas fa-file-import"></i> {{ __('Import') }}
                            </a>

                            @if ($product_setting->physical_product == 1 && $product_setting->digital_product == 1)
                                <!-- Both enabled: show dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="productType"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-plus"></i> {{ __('Add Product') }}
                                    </button>

                                    <div class="dropdown-menu" aria-labelledby="productType">
                                        <a class="dropdown-item"
                                            href="{{ route('admin.product.create', ['type' => 'physical']) }}">
                                            {{ __('Physical Product') }}
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('admin.product.create', ['type' => 'digital']) }}">
                                            {{ __('Digital Product') }}
                                        </a>
                                    </div>
                                </div>
                            @elseif ($product_setting->physical_product == 1 || $product_setting->digital_product == 1)
                                <!-- Only one enabled: direct button -->
                                <a href="{{ route('admin.product.create', [
                                    'type' => $product_setting->physical_product == 1 ? 'physical' : 'digital',
                                ]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> {{ __('Add Product') }}
                                </a>
                            @else
                                <!-- Both disabled: no button -->
                            @endif


                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($products) == 0)
                        <h5 class="text-center">{{ __('NO PRODUCT FOUND') }} !</h5>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Image') }}</th>
                                    <th scope="col">{{ __('Title') }}</th>
                                    <th scope="col">{{ __('Category') }}</th>
                                    <th scope="col">{{ __('Variant Type') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Featured') }}</th>
                                    <th scope="col">{{ __('Stock') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($products as $key => $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $product->id }}">
                                            </td>
                                            <td>
                                                <img src="{{ asset('assets/img/product/' . $product->thumbnail) }}"
                                                    alt="product">
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ route('frontend.shop.details', ['id' => $product->id]) }}">
                                                    {{ truncateString($product->title, 50) }}
                                                </a>
                                            </td>

                                            <td>
                                                {{ $product->categoryName }}
                                            </td>
                                            <td>
                                                @if ((int) $product->has_variants === 1)
                                                    <span class="badge bg-info">{{ __('Yes') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('No') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->status == 1)
                                                    <span class="badge bg-success changeStatusBtn"
                                                        data-id="{{ $product->id }}" data-value="{{ $product->status }}"
                                                        data-url="{{ route('admin.product.status_change') }}">{{ __('Active') }}</span>
                                                @else
                                                    <span class="badge bg-danger changeStatusBtn"
                                                        data-id="{{ $product->id }}" data-value="{{ $product->status }}"
                                                        data-url="{{ route('admin.product.status_change') }}">{{ __('Inactive') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($hasFeaturedColumn)
                                                    <form action="{{ route('admin.product.featured_update') }}"
                                                        method="post" class="d-inline-block">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <input type="hidden" name="featured"
                                                            value="{{ (int) $product->featured === 1 ? 0 : 1 }}">
                                                        <button type="submit"
                                                            class="btn btn-sm {{ (int) $product->featured === 1 ? 'btn-warning' : 'btn-outline-secondary' }}">
                                                            <i class="fas fa-star me-1"></i>
                                                            {{ (int) $product->featured === 1 ? __('Yes') : __('No') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('N/A') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $displayStock =
                                                        (int) ($product->available_stock ?? $product->stock);
                                                @endphp
                                                @if ($displayStock > 0)
                                                    <span class="badge bg-primary">{{ $displayStock }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Out of stock') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons product-list-actions">
                                                    <a href="{{ route('admin.product.edit', ['id' => $product->id]) }}"
                                                        class="btn btn-sm edit-button product-action-btn">
                                                        <span class="fas fa-edit"></span>
                                                        <span class="product-action-label">{{ __('Edit') }}</span>
                                                    </a>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-warning product-action-btn flashSaleBtn"
                                                        data-bs-toggle="modal" data-bs-target="#flashSaleModal"
                                                        data-product-id="{{ $product->id }}"
                                                        data-title="{{ $product->title }}"
                                                        data-current-price="{{ $product->has_variants == 1 ? __('Variant Product') : $product->current_price }}"
                                                        data-flash-sale-status="{{ $product->flash_sale_status ?? 0 }}"
                                                        data-flash-sale-price="{{ $product->flash_sale_price !== null ? $product->flash_sale_price : '' }}"
                                                        data-flash-sale-start-at="{{ !empty($product->flash_sale_start_at) ? \Illuminate\Support\Carbon::parse($product->flash_sale_start_at)->format('Y-m-d\\TH:i') : '' }}"
                                                        data-flash-sale-end-at="{{ !empty($product->flash_sale_end_at) ? \Illuminate\Support\Carbon::parse($product->flash_sale_end_at)->format('Y-m-d\\TH:i') : '' }}">
                                                        <span class="fas fa-bolt"></span>
                                                        <span class="product-action-label">{{ __('Flash Sale') }}</span>
                                                    </button>
                                                    <form class="deleteForm d-inline-block"
                                                        action="{{ route('admin.product.delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $product->id }}"
                                                            name="product_id">
                                                        <button
                                                            class="btn btn-sm deleteBtn delete-button product-action-btn"
                                                            type="button">
                                                            <span class="fas fa-trash"></span>
                                                            <span class="product-action-label">{{ __('Delete') }}</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <div class="card-footer py-2">
            </div>
        </div>
    </div>

    <div class="modal fade" id="flashSaleModal" tabindex="-1" aria-labelledby="flashSaleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="flashSaleModalLabel">{{ __('Update Flash Sale') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="flashSaleForm" action="{{ route('admin.product.flash_sale_update') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="flash_product_id">

                        <div class="form-group mb-3">
                            <label>{{ __('Product') }}</label>
                            <input type="text" id="flash_product_title" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ __('Current Price') }}</label>
                            <input type="text" id="flash_current_price" class="form-control" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ __('Status') }} <span class="text-danger">**</span></label>
                            <select name="flash_sale_status" id="flash_sale_status" class="form-select" required>
                                <option value="1">{{ __('Enable') }}</option>
                                <option value="0">{{ __('Disable') }}</option>
                            </select>
                            <p id="err_flash_sale_status" class="text-danger em"></p>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ __('Discount') }} (%)<span class="text-danger">**</span></label>
                            <input type="number" step="1" min="1" name="flash_sale_price"
                                id="flash_sale_price" class="form-control">
                            <p id="err_flash_sale_price" class="text-danger em"></p>
                        </div>

                        <div class="form-group mb-3">
                            <label>{{ __('Start Date') }}<span class="text-danger">**</span></label>
                            <input type="datetime-local" name="flash_sale_start_at" id="flash_sale_start_at"
                                class="form-control">
                            <p id="err_flash_sale_start_at" class="text-danger em"></p>
                        </div>

                        <div class="form-group mb-0">
                            <label>{{ __('End Date') }} <span class="text-danger">**</span></label>
                            <input type="datetime-local" name="flash_sale_end_at" id="flash_sale_end_at"
                                class="form-control">
                            <p id="err_flash_sale_end_at" class="text-danger em"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.product.search')
@endsection
