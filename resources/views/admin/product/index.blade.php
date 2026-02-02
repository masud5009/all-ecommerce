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
                                    <th scope="col">{{ __('Variations') }}</th>
                                    <th scope="col">{{ __('Category') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
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
                                                <a href="{{ route('admin.product.variant', ['product_id' => $product->id, 'language' => request()->input('language')]) }}"
                                                    class="btn btn-sm btn-primary"><i
                                                        class="fas fa-edit"></i>{{ __('Manage') }}</a>
                                            </td>
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
@endsection
