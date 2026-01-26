@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Inventory Management') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Adjust Stock') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <!--filter start-->
        <div class="card shadow mb-3">
            <div class="card-body">
                <form id="searchForm" action="{{ url()->current() }}" method="GET">
                    <input type="hidden" name="language" value="{{ request()->input('language') }}">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="{{ __('Search by title/SKU') }}"
                                    name="title" value="{{ request()->input('title') }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <select name="category" class="select2 form-control"
                                    onchange="document.getElementById('searchForm').submit()">
                                    <option disabled selected>{{ __('Select Category') }}</option>
                                    <option value="">{{ __('All') }}</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(request()->input('category') == $category->id)>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <select name="stock_type" class="select2 form-control"
                                    onchange="document.getElementById('searchForm').submit()">
                                    <option disabled selected>{{ __('Select Stock Type') }}</option>
                                    <option value="">{{ __('All') }}</option>
                                    <option value="stock_in" @selected(request()->input('stock_type') == 'stock_in')>{{ __('Stock In') }}</option>
                                    <option value="stock_out" @selected(request()->input('stock_type') == 'stock_out')>{{ __('Stock Out') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 mt-2">
                            <div class="form-group">
                                <h5>{{ __('Total Products') }}: {{ $products->total() }}</h5>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!--filter end -->
        <div class="card shadow">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-title">
                            <h5>{{ __('Inventory') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        @if (count($products) > 0)
                            <div class="col-lg-2">
                                {{ $products->links() }}
                            </div>
                        @endif
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
                                    <th scope="col">{{ __('#') }}</th>
                                    <th scope="col">{{ __('Product') }}</th>
                                    <th scope="col">{{ __('SKU') }}</th>
                                    <th scope="col">{{ __('Current Stock') }}</th>
                                    <th scope="col">{{ __('Current Price') }}</th>
                                    <th scope="col">{{ __('Previous Price') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        @php
                                            $totalVariation = check_variation($product->id);
                                            $variations = App\Http\Helpers\Common::getVariations(
                                                $product->id,
                                                $currentLang->id,
                                            );
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ truncateString($product->title, 30) }}</td>
                                            <td>{{ $product->sku }}</td>
                                            <td>
                                                @if ($totalVariation > 0)
                                                    <em>
                                                        <span class="badge bg-primary cursor-pointer" data-bs-toggle="modal"
                                                            data-bs-target="#variationModal_{{ $product->sku }}_{{ $product->id }}">
                                                            {{ __('Show Variations') }}</span></em>
                                                @elseif($product->type == 'Digital')
                                                    <em>{{ __('This Product is not physical') }}</em>
                                                @else
                                                    {{ $product->stock }}
                                                @endif
                                            </td>
                                            <td>{{ currency_symbol($product->current_price) }}</td>
                                            <td>{{ currency_symbol($product->previous_price) }}</td>
                                            <td>
                                                <div class="action-buttons">
                                                    @if ($totalVariation > 0)
                                                        <a href="{{ route('admin.product.variant', ['product_id' => $product->id, 'language' => $currentLang->code]) }}"
                                                            class="btn btn-sm edit-button">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @elseif($product->type == 'Digital')
                                                        <em>{{ __('This Product is not physical') }}</em>
                                                    @else
                                                        <a href="#" class="btn btn-sm editBtn edit-button"
                                                            data-bs-toggle="modal" data-bs-target="#editModal"
                                                            data-id="{{ $product->id }}"
                                                            data-stock="{{ $product->stock }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @if (!empty($variations))
                                            <div class="modal fade"
                                                id="variationModal_{{ $product->sku }}_{{ $product->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header border-0">
                                                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row px-3">
                                                                @foreach ($variations as $variation)
                                                                    @php
                                                                        $optionNames = json_decode(
                                                                            $variation->option_name,
                                                                        );
                                                                        $optionPrices = json_decode(
                                                                            $variation->option_price,
                                                                        );
                                                                        $optionStocks = json_decode(
                                                                            $variation->option_stock,
                                                                        );
                                                                    @endphp
                                                                    <div class="col-lg-6 mb-2">
                                                                        <h6><strong>{{ $variation->variant_name }}</strong>
                                                                        </h6>
                                                                        @foreach ($optionNames as $key => $optionName)
                                                                            <ul class="list-unstyled">
                                                                                <li>
                                                                                    {{ $optionName }} -
                                                                                    ${{ $optionPrices[$key] }} (Stock:
                                                                                    {{ $optionStocks[$key] }})
                                                                                </li>
                                                                            </ul>
                                                                        @endforeach
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
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
    @include('admin.inventory.stock-list.stock-modal')
@endsection
