@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('POS') }}</a>
            </li>
        </ol>
    </nav>
    <div class="col-lg-12 mb-5">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="" class="mb-1">{{ __('Shipping Amount') }}</label>
                        <div class="input-group">
                            <input type="number" id="shipping_amount" class="form-control" placeholder="Amount"
                                aria-label="Amount" aria-describedby="basic-addon2" value="0" min="0.1">
                            <span class="input-group-text text-danger" id="basic-addon2"><i class="fas fa-minus"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="" class="mb-1">{{ __('Discount Amount') }}(%)</label>
                        <div class="input-group">
                            <input type="number" id="discount_amount" class="form-control" placeholder="Amount"
                                aria-label="Amount" aria-describedby="basic-addon2" value="0" min="0.1">
                            <span class="input-group-text text-success" id="basic-addon2"><i class="fas fa-plus"></i></span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="" class="mb-1">{{ __('Tax Amount') }}</label>
                        <div class="input-group">
                            <input type="number" id="tax_amount" class="form-control" placeholder="Amount"
                                aria-label="Amount" aria-describedby="basic-addon2" value="0" min="0.1">
                            <span class="input-group-text text-danger" id="basic-addon2"><i class="fas fa-minus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Product List -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header search-product">
                        <input type="text" id="product-search" placeholder="Search product..." class="form-control">
                        <div class="pos-loader d-none"><i class="fas fa-spinner fa-spin"></i></div>
                    </div>
                    <div class="card-body">
                        <div id="pos-product" class="pos-product row">
                            @if (count($products) == 0)
                                <div class="text-center">
                                    <h5 class="w-100 mb-3">{{ __('NO PRODUCT FOUND') }}</h5>
                                    <i class="fas fa-sad-tear fs-1" aria-hidden="true"></i>
                                </div>
                            @else
                                @foreach ($products as $product)
                                    <div class="col-lg-3">
                                        <div class="product-item" data-id="{{ $product->id }}"
                                            data-name="{{ $product->title }}" data-price="{{ $product->price }}">
                                            <div class="product-image">
                                                <img src="{{ asset('assets/img/product/' . $product->thumbnail) }}"
                                                    alt="{{ $product->title }}">
                                                <div class="hover-icon" data-toggle="tooltip" data-placement="top"
                                                    data-title="{{ truncateString($product->title, 30) }}"
                                                    data-id="{{ $product->id }}" title="Add to Cart"
                                                    data-variation="{{ check_variation($product->id) > 0 ? 'yes' : 'null' }}"
                                                    data-id="{{ $product->id }}"><i class="fas fa-cart-plus"></i>
                                                </div>
                                            </div>
                                            <p class="title">{{ truncateString($product->title, 25) }}</p>
                                            <span class="price">{{ currency_symbol($product->current_price) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Checkout -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-8">
                                <select name="user" id="user" class="select2 form-control">
                                    <option disabled selected>{{ __('Select a customer') }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 flex-end">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#billingModal">{{ __('Add Customer') }}</button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <span class="text-danger">
                                <span class="fw-bold">{{ __('Note:') }} </span>
                                {{ __('Add or select a customer to include billing details.') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="checkout-list" class="pos-product-div">
                            @if (empty($checkoutProducts))
                                <h6 class="empty-checkout">
                                    <i class="fas fa-sad-tear"></i>
                                    <span>{{ __('ADD PRODUCT') }}</span>
                                </h6>
                            @else
                                <div class="checkout-product">
                                    @foreach ($checkoutProducts as $checkoutProduct)
                                        <div class="product-wraper">
                                            <span class="product-title">
                                                {{ truncateString(@$checkoutProduct['title'], 45) }}
                                            </span><br>
                                            <div class="product">
                                                <div>
                                                    <small><strong>{{ __('Item Price') }}:</strong>
                                                        <span class="product-price"
                                                            data-price="{{ @$checkoutProduct['subtotal'] }}">{{ currency_symbol(@$checkoutProduct['subtotal']) }}</span>
                                                    </small>
                                                    <br>
                                                    @if (!is_null($checkoutProduct['variations']))
                                                        <small><strong>{{ __('Variations') }}:</strong>
                                                            @foreach ($checkoutProduct['variations'] as $variation)
                                                                <p class="p-0 m-0">{{ $variation['variation_name'] }} -
                                                                    {{ $variation['option_name'] }}
                                                                    ({{ currency_symbol($variation['price']) }})
                                                                </p>
                                                            @endforeach
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="quantity-controls">
                                                        <button class="btn btn-sm btn-outline-secondary decrement"
                                                            data-productid="{{ @$checkoutProduct['id'] }}">-</button>
                                                        <input type="number" id="quantity"
                                                            value="{{ @$checkoutProduct['quantity'] }}" min="1"
                                                            readonly class="form-control mx-2 text-center"
                                                            style="width: 50px;">
                                                        <button class="btn btn-sm btn-outline-secondary increment"
                                                            data-productid="{{ @$checkoutProduct['id'] }}">+</button>
                                                    </div>
                                                    <button class="remove-product mx-2"
                                                        data-removeid="{{ @$checkoutProduct['id'] }}"><i
                                                            class="fas fa-times"></i></button>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if (!empty($checkoutProducts))
                                <div class="checkout-btn-div">
                                    <div class="row">
                                        <div class="col-lg-8"><span>{{ __('Total Items') }}:</span></div>
                                        <div class="col-lg-4"><span>{{ $totalItems }}</span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <span>{{ __('Shipping Charge') }} <span class="text-danger">(-)</span>:</span>
                                        </div>
                                        <div class="col-lg-4">{{ $websiteInfo->currency_symbol }}<span
                                                class="shipping_amount">0.00</span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <span>{{ __('Discount') }} <span
                                                    class="discount_percent">(0.00%)</span></span>
                                        </div>
                                        <div class="col-lg-4">
                                            {{ $websiteInfo->currency_symbol }}
                                            <span class="discount_amount">0.00</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8"><span>{{ __('Tax') }} <span
                                                    class="text-danger">(-)</span>:</span></div>
                                        <div class="col-lg-4">{{ $websiteInfo->currency_symbol }}<span
                                                class="tax_amount">0.00</span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8"><span>{{ __('Total Price') }}:</span></div>
                                        <div class="col-lg-4"><span
                                                id="total-price">{{ currency_symbol($totalAmount) }}</span></div>
                                    </div>
                                    <form action="{{ route('admin.pos_mangment.checkout') }}" id="pos_checkout_form"
                                        method="post">
                                        @csrf
                                        <input type="hidden" name="tax_amount" class="tax_amount" value="0">
                                        <input type="hidden" name="discount_amount" class="discount_amount"
                                            value="0">
                                        <input type="hidden" name="shipping_amount" class="shipping_amount"
                                            value="0">
                                        <button class="btn btn-success w-100 mt-3" data-loading="false"
                                            id="pos_checkout">{{ __('Checkout Now') }}</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('admin.pos.variation-modal')
    @includeIf('admin.pos.billing-modal')
@endsection
@section('script')
    <script>
        const addUrl = "{{ route('admin.pos_mangment.add_product') }}";
        const variationUrl = "{{ route('admin.pos_mangment.get_variation') }}";
        const couponUrl = "{{ route('admin.pos_mangment.applyCoupon') }}";
        const searchUrl = "{{ route('admin.pos_mangment.itemSearch') }}";
        let totalAmount = "{{ $totalAmount }}" || 0;
    </script>
    <script src="{{ asset('assets/admin/js/pos.js') }}"></script>
    @if (session('success'))
        <script>
            const sound = new Audio("{{ asset('assets/audio/order_confirm_sound.mp3') }}");
            sound.play().catch(function(e) {
                console.error("Audio failed to play:", e);
            });
        </script>
    @endif
@endsection
