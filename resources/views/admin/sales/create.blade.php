@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Sales Management') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Create Order') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">{{ __('Create Order') }}</h5>
                    <a href="{{ route('admin.sales', ['language' => app('defaultLang')->code]) }}"
                        class="btn btn-primary btn-sm">
                        <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif

                <form action="{{ route('admin.sales.store') }}" method="post">
                    @csrf

                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>{{ __('Billing Name') }} <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" name="billing_name"
                                    value="{{ old('billing_name') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>{{ __('Billing Email') }} <span class="text-danger">**</span></label>
                                <input type="email" class="form-control" name="billing_email"
                                    value="{{ old('billing_email') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label>{{ __('Billing Phone') }} <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" name="billing_phone"
                                    value="{{ old('billing_phone') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Billing Address') }} <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" name="billing_address"
                                    value="{{ old('billing_address') }}" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Billing City') }}</label>
                                <input type="text" class="form-control" name="billing_city"
                                    value="{{ old('billing_city') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ __('Shipping Address') }}</label>
                                <input type="text" class="form-control" name="shipping_address"
                                    value="{{ old('shipping_address') }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Delivery Date') }}</label>
                                <input type="date" class="form-control" name="delivery_date"
                                    value="{{ old('delivery_date') }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Payment Method') }} <span class="text-danger">**</span></label>
                                <select name="payment_method" class="form-select" required>
                                    @php
                                        $paymentMethod = old('payment_method', 'Cash Payment');
                                    @endphp
                                    <option value="Cash Payment" @selected($paymentMethod === 'Cash Payment')>{{ __('Cash Payment') }}</option>
                                    <option value="Bank" @selected($paymentMethod === 'Bank')>{{ __('Bank') }}</option>
                                    <option value="Other" @selected($paymentMethod === 'Other')>{{ __('Other') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Gateway') }} <span class="text-danger">**</span></label>
                                <select name="gateway" class="form-select" required>
                                    @php
                                        $gateway = old('gateway', 'Offline');
                                    @endphp
                                    <option value="Offline" @selected($gateway === 'Offline')>{{ __('Offline') }}</option>
                                    <option value="Manual" @selected($gateway === 'Manual')>{{ __('Manual') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Payment Status') }} <span class="text-danger">**</span></label>
                                <select name="payment_status" class="form-select" required>
                                    @php
                                        $paymentStatus = old('payment_status', 'completed');
                                    @endphp
                                    <option value="completed" @selected($paymentStatus === 'completed')>{{ __('Completed') }}</option>
                                    <option value="pending" @selected($paymentStatus === 'pending')>{{ __('Pending') }}</option>
                                    <option value="rejected" @selected($paymentStatus === 'rejected')>{{ __('Rejected') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Order Status') }} <span class="text-danger">**</span></label>
                                <select name="order_status" class="form-select" required>
                                    @php
                                        $orderStatus = old('order_status', 'completed');
                                    @endphp
                                    <option value="completed" @selected($orderStatus === 'completed')>{{ __('Completed') }}</option>
                                    <option value="pending" @selected($orderStatus === 'pending')>{{ __('Pending') }}</option>
                                    <option value="rejected" @selected($orderStatus === 'rejected')>{{ __('Rejected') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="mb-0">{{ __('Order Items') }}</h6>
                        <button type="button" class="btn btn-sm btn-primary" id="addItemBtn">
                            <i class="fas fa-plus"></i> {{ __('Add Item') }}
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="min-width: 280px">{{ __('Item') }}</th>
                                    <th style="min-width: 120px">{{ __('Qty') }}</th>
                                    <th style="min-width: 140px">{{ __('Price') }}</th>
                                    <th style="min-width: 140px">{{ __('Line Total') }}</th>
                                    <th style="min-width: 80px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="orderItemsBody"></tbody>
                        </table>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Discount Amount') }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="discount_amount"
                                    id="discountInput" value="{{ old('discount_amount', 0) }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Tax Amount') }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="tax_amount"
                                    id="taxInput" value="{{ old('tax_amount', 0) }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Shipping Charge') }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="shipping_charge"
                                    id="shippingInput" value="{{ old('shipping_charge', 0) }}">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Subtotal') }}</label>
                                <div class="form-control bg-light" id="subtotalText">0</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('Total') }}</label>
                                <div class="form-control bg-light" id="totalText">0</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success">{{ __('Create Order') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            var body = document.getElementById('orderItemsBody');
            var addBtn = document.getElementById('addItemBtn');
            var discountInput = document.getElementById('discountInput');
            var taxInput = document.getElementById('taxInput');
            var shippingInput = document.getElementById('shippingInput');
            var subtotalText = document.getElementById('subtotalText');
            var totalText = document.getElementById('totalText');
            var searchUrl = "{{ route('admin.sales.items') }}";

            function parseNumber(value) {
                var num = parseFloat(value);
                return isNaN(num) ? 0 : num;
            }

            function recalcTotals() {
                var subtotal = 0;
                var rows = body.querySelectorAll('tr');
                rows.forEach(function (row) {
                    var qty = parseNumber(row.querySelector('.qty-input').value);
                    var price = parseNumber(row.querySelector('.price-input').value);
                    var lineTotal = qty * price;
                    row.querySelector('.line-total').textContent = lineTotal.toFixed(2);
                    subtotal += lineTotal;
                });

                subtotalText.textContent = subtotal.toFixed(2);
                var discount = parseNumber(discountInput.value);
                var tax = parseNumber(taxInput.value);
                var shipping = parseNumber(shippingInput.value);
                var total = (subtotal - discount) + tax + shipping;
                totalText.textContent = total.toFixed(2);
            }

            function updateRowIndexes() {
                var rows = body.querySelectorAll('tr');
                rows.forEach(function (row, index) {
                    row.querySelector('.item-select').setAttribute('name', 'items[' + index + '][select]');
                    row.querySelector('.product-id').setAttribute('name', 'items[' + index + '][product_id]');
                    row.querySelector('.variant-id').setAttribute('name', 'items[' + index + '][variant_id]');
                    row.querySelector('.qty-input').setAttribute('name', 'items[' + index + '][qty]');
                    row.querySelector('.price-input').setAttribute('name', 'items[' + index + '][price]');
                });
            }

            function initSelect2(select, row) {
                $(select).select2({
                    width: '100%',
                    placeholder: "{{ __('Search product or SKU') }}",
                    minimumInputLength: 1,
                    ajax: {
                        url: searchUrl,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results || [],
                                pagination: {
                                    more: data.pagination ? data.pagination.more : false
                                }
                            };
                        },
                        cache: true
                    }
                });

                $(select).on('select2:select', function (e) {
                    var data = e.params.data || {};
                    var productIdInput = row.querySelector('.product-id');
                    var variantIdInput = row.querySelector('.variant-id');
                    var priceInput = row.querySelector('.price-input');
                    var stockInfo = row.querySelector('.stock-info');

                    productIdInput.value = data.product_id || '';
                    variantIdInput.value = data.variant_id || '';
                    priceInput.value = data.price !== undefined ? data.price : 0;

                    if (data.type === 'digital') {
                        stockInfo.textContent = "{{ __('Digital product') }}";
                    } else if (data.available !== null && data.available !== undefined) {
                        var label = data.track_serial === 1 ? "{{ __('Serial stock') }}" : "{{ __('Available') }}";
                        stockInfo.textContent = label + ': ' + data.available;
                    } else {
                        stockInfo.textContent = '';
                    }

                    recalcTotals();
                });

                $(select).on('select2:clear', function () {
                    var productIdInput = row.querySelector('.product-id');
                    var variantIdInput = row.querySelector('.variant-id');
                    var priceInput = row.querySelector('.price-input');
                    var stockInfo = row.querySelector('.stock-info');

                    productIdInput.value = '';
                    variantIdInput.value = '';
                    priceInput.value = '';
                    stockInfo.textContent = '';
                    recalcTotals();
                });
            }

            function createRow() {
                var row = document.createElement('tr');
                row.innerHTML =
                    '<td>' +
                    '  <select class="form-select item-select" data-placeholder="{{ __('Search product or SKU') }}"></select>' +
                    '  <input type="hidden" class="product-id" />' +
                    '  <input type="hidden" class="variant-id" />' +
                    '  <div class="small text-muted stock-info"></div>' +
                    '</td>' +
                    '<td><input type="number" min="1" value="1" class="form-control qty-input" /></td>' +
                    '<td><input type="number" min="0" step="0.01" class="form-control price-input" /></td>' +
                    '<td class="line-total">0</td>' +
                    '<td><button type="button" class="btn btn-sm btn-danger remove-row">{{ __('Remove') }}</button></td>';

                var qtyInput = row.querySelector('.qty-input');
                var priceInput = row.querySelector('.price-input');
                var select = row.querySelector('.item-select');

                qtyInput.addEventListener('input', recalcTotals);
                priceInput.addEventListener('input', recalcTotals);

                row.querySelector('.remove-row').addEventListener('click', function () {
                    $(select).select2('destroy');
                    row.remove();
                    updateRowIndexes();
                    recalcTotals();
                });

                body.appendChild(row);
                initSelect2(select, row);
                updateRowIndexes();
            }

            addBtn.addEventListener('click', function () {
                createRow();
            });

            discountInput.addEventListener('input', recalcTotals);
            taxInput.addEventListener('input', recalcTotals);
            shippingInput.addEventListener('input', recalcTotals);

            createRow();
            recalcTotals();
        })();
    </script>
@endsection
