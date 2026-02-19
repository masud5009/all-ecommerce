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
        $hasProductFilter = request()->filled('search') || request()->filled('status') || request()->filled('stock');
    @endphp

    <div class="col-lg-12">
        <div class="card">
            <x-bulk-delete :url="route('admin.product.bulk_delete')" itemTextName="products" />
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Products') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-8 col-sm-6">
                        <div class="info-header-content">
                            <button class="btn btn-danger btn-sm  d-none bulk-delete"
                                data-href="{{ route('admin.product.bulk_delete') }}">
                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                            </button>

                            <button type="button"
                                class="btn btn-sm me-2 {{ $hasProductFilter ? 'btn-primary' : 'btn-outline-secondary' }}"
                                data-bs-toggle="offcanvas" data-bs-target="#productFilterOffcanvas"
                                aria-controls="productFilterOffcanvas">
                                <i class="fas fa-filter"></i>
                            </button>

                            @if ($hasProductFilter)
                                <a href="{{ route('admin.product', ['language' => request('language', app('defaultLang')->code)]) }}"
                                    class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="fas fa-times"></i> {{ __('Clear Filter') }}
                                </a>
                            @endif

                            <a href="{{ route('admin.product.variant.restock') }}" class="btn btn-outline-primary btn-sm me-2">
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
                                    <th scope="col">{{ __('Status') }}</th>
                                    <th scope="col">{{ __('Stock') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>
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
                                                {{ truncateString($product->title, 20) }}</td>

                                            <td>
                                                {{ $product->categoryName }}
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
                                                @if ((int) $product->stock > 0)
                                                    <span class="badge bg-primary">{{ (int) $product->stock }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('Out of stock') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.product.edit', ['id' => $product->id]) }}"
                                                        class="btn btn-sm edit-button">
                                                        <span class="fas fa-edit"></span>
                                                    </a>
                                                    <form class="deleteForm d-inline-block"
                                                        action="{{ route('admin.product.delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" value="{{ $product->id }}"
                                                            name="product_id">
                                                        <button class="btn btn-sm deleteBtn delete-button" type="button">
                                                            <span class="fas fa-trash"></span>
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

@include('admin.product.search')
@endsection
