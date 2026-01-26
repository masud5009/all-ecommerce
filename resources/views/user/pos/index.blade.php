@extends('user.layout')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/pos.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <nav aria-label="breadcrumb" class="breadcrumb mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('user.dashboard') }}"><i class="fas fa-home"></i></a>
                </li>
                <li class="breadcrumb-item active">{{ __('Restaurant POS') }}</li>
            </ol>
        </nav>

        <!-- Quick Stats -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">{{ __("Today's Sales") }}</h5>
                                <h3 class="mb-0">৳<span id="todaySales">0</span></h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-shopping-bag fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">{{ __('Orders') }}</h5>
                                <h3 class="mb-0"><span id="todayOrders">0</span></h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-receipt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">{{ __('Occupied Tables') }}</h5>
                                <h3 class="mb-0"><span id="occupiedTables">0</span>/<span id="totalTables">12</span></h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-utensils fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 class="mb-0">{{ __('Kitchen Orders') }}</h5>
                                <h3 class="mb-0"><span id="kitchenOrders">0</span></h3>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-fire fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left: Tables & Products -->
            <div class="col-lg-8">
                <!-- Table Management -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-chair"></i> {{ __('Table Management') }}</h5>
                        <button class="btn btn-sm btn-light" onclick="showKitchenOrders()">
                            <i class="fas fa-fire"></i> {{ __('Kitchen Orders') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row" id="tableGrid">
                            @foreach ($tables as $table)
                                <div class="col-6 col-md-4 col-lg-3 mb-3">
                                    <div class="card table-card {{ $table->status == 'available' ? 'border-success' : ($table->status == 'occupied' ? 'border-warning' : 'border-danger') }} clickable"
                                        onclick="selectTable({{ $table->id }})">
                                        <div class="card-body text-center p-3">
                                            <h5 class="card-title">{{ $table->name ?: 'Table ' . $table->number }}</h5>
                                            <p class="card-text mb-1">
                                                <i class="fas fa-users"></i> {{ $table->capacity }} Persons
                                            </p>
                                            <span
                                                class="badge {{ $table->status == 'available' ? 'bg-success' : ($table->status == 'occupied' ? 'bg-warning' : 'bg-danger') }}">
                                                {{ strtoupper($table->status) }}
                                            </span>
                                            @if ($table->status === 'occupied' && $table->current_order)
                                                <div class="mt-2">
                                                    <small class="text-muted">Order #{{ $table->current_order }}</small>
                                                </div>
                                            @endif
                                            @if ($table->qr_image)
                                                <div class="mt-2">
                                                    <small class="text-primary">
                                                        <i class="fas fa-qrcode"></i> QR Available
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Product Grid - FIXED: Duplicate removed -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-utensils"></i> {{ __('Menu') }}</h5>
                        <div>
                            <button class="btn btn-sm btn-light me-2" onclick="showHeldBills()">
                                <i class="fas fa-pause"></i> {{ __('Held Bills') }} (<span id="heldBadge">0</span>)
                            </button>
                            <button class="btn btn-sm btn-light" onclick="showOrderHistory()">
                                <i class="fas fa-history"></i> {{ __('Order History') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="category" id="cat-all" checked>
                                <label class="btn btn-outline-primary" for="cat-all">All</label>

                                @foreach ($categories as $category)
                                    <input type="radio" class="btn-check" name="category"
                                        id="cat-{{ $category->slug }}">
                                    <label class="btn btn-outline-primary"
                                        for="cat-{{ $category->slug }}">{{ $category->name }}</label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="input-group mb-3">
                            <input type="text" id="searchProduct" class="form-control"
                                placeholder="Search menu items..." autocomplete="off">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>

                        <!-- Product Grid -->
                        <div class="row" id="productGrid">
                            @forelse($products as $product)
                                <div class="col-6 col-md-4 col-lg-3 mb-3 product-item"
                                    data-name="{{ strtolower($product['name']) }}"
                                    data-category="{{ $product['category'] }}"
                                    data-cooking-time="{{ $product['cooking_time'] }}">
                                    <div class="card h-100 product-card border-0 shadow-sm clickable"
                                        onclick="addToCart({{ $product['id'] }}, '{{ addslashes($product['name']) }}', {{ $product['price'] }}, '{{ $product['image'] }}', {{ $product['cooking_time'] }}, {{ $product['is_veg'] ? 'true' : 'false' }})">
                                        <div class="position-relative">
                                            <img src="{{ $product['image'] }}" class="card-img-top"
                                                alt="{{ $product['name'] }}" style="height: 100px; object-fit: cover;">
                                            @if ($product['cooking_time'] > 0)
                                                <span
                                                    class="position-absolute top-0 start-0 badge bg-warning text-dark m-1">
                                                    <i class="fas fa-clock"></i> {{ $product['cooking_time'] }}m
                                                </span>
                                            @endif
                                            @if ($product['is_veg'])
                                                <span class="position-absolute top-0 end-0 badge bg-success m-1">Veg</span>
                                            @else
                                                <span
                                                    class="position-absolute top-0 end-0 badge bg-danger m-1">Non-Veg</span>
                                            @endif
                                        </div>
                                        <div class="card-body p-2 text-center">
                                            <h6 class="card-title mb-1">{{ $product['name'] }}</h6>
                                            <p class="text-primary fw-bold mb-0">{{ currency_symbol($product['price']) }}</p>
                                            <small class="text-muted">{{ $product['category_name'] }}</small>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-utensils fa-3x mb-3"></i>
                                        <p>No products available</p>
                                        <small>Add products from the admin panel</small>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Cart & Order Details -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart"></i>
                            {{ __('Order') }}
                            <span id="tableBadge" class="badge bg-light text-dark ms-1">No Table</span>
                            (<span id="cartCount">0</span>)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <!-- Order Type & Table -->
                        <div class="p-3 border-bottom">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-label">Order Type</label>
                                    <select id="orderType" class="form-select" onchange="toggleTableSelection()">
                                        <option value="dine-in">Dine In</option>
                                        <option value="takeaway">Takeaway</option>
                                        <option value="delivery">Delivery</option>
                                    </select>
                                </div>
                                <div class="col-6" id="tableSelection">
                                    <label class="form-label">Table</label>
                                    <select id="selectedTable" class="form-select">
                                        <option value="">Select Table</option>
                                        <!-- Tables will be populated dynamically -->
                                    </select>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label mb-0">Customer</label>
                                    <button class="btn btn-sm btn-outline-primary" onclick="showCustomerModal()">
                                        <i class="fas fa-user"></i> Select
                                    </button>
                                </div>
                                <div id="customerInfo" class="text-muted small">
                                    Walk-in Customer
                                </div>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div id="cartItems" style="max-height: 300px; overflow-y: auto;">
                            <!-- Cart items will be injected here -->
                        </div>

                        <!-- Order Notes -->
                        <div class="p-3 border-top">
                            <label class="form-label">Order Notes</label>
                            <textarea id="orderNotes" class="form-control" rows="2" placeholder="Special instructions..."></textarea>
                        </div>

                        <!-- Totals -->
                        <div class="p-3 border-top">
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Subtotal') }}</span>
                                <strong id="subtotal">{{ $websiteInfo->currency_symbol }}0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Service Charge (10%)') }}</span>
                                <strong id="serviceCharge">{{ $websiteInfo->currency_symbol }}0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('VAT (5%)') }}</span>
                                <strong id="vat">{{ $websiteInfo->currency_symbol }}0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ __('Discount') }}</span>
                                <div class="d-flex align-items-center">
                                    <input type="number" id="discount" class="form-control form-control-sm me-1"
                                        value="0" min="0" style="width: 80px;">
                                    <span>{{ $websiteInfo->currency_symbol }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between text-lg">
                                <strong>{{ __('Total') }}</strong>
                                <strong id="grandTotal"
                                    class="text-success">{{ $websiteInfo->currency_symbol }}0</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row g-2">
                            <div class="col-4">
                                <button class="btn btn-warning w-100" onclick="holdBill()">
                                    <i class="fas fa-pause"></i> Hold
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-info w-100" onclick="sendToKitchen()">
                                    <i class="fas fa-fire"></i> Kitchen
                                </button>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-danger w-100" onclick="clearCart()">
                                    <i class="fas fa-trash"></i> Clear
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <select id="paymentMethod" class="form-select mb-2">
                                <option value="cash">{{ __('Cash') }}</option>
                                <option value="card">{{ __('Card') }}</option>
                                <option value="mobile">{{ __('Mobile Banking') }}</option>
                            </select>
                            <button class="btn btn-primary w-100" onclick="checkout()">
                                <i class="fas fa-check"></i> {{ __('Checkout') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action"
                            onclick="selectCustomer(null, 'Walk-in Customer')">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Walk-in Customer</h6>
                            </div>
                            <small class="text-muted">No customer details</small>
                        </a>
                        @php
                            $customers = [
                                [
                                    'id' => 1,
                                    'name' => 'John Doe',
                                    'phone' => '0123456789',
                                    'email' => 'john@example.com',
                                ],
                                [
                                    'id' => 2,
                                    'name' => 'Jane Smith',
                                    'phone' => '0123456790',
                                    'email' => 'jane@example.com',
                                ],
                                [
                                    'id' => 3,
                                    'name' => 'Mike Johnson',
                                    'phone' => '0123456791',
                                    'email' => 'mike@example.com',
                                ],
                            ];
                        @endphp
                        @foreach ($customers as $c)
                            <a href="#" class="list-group-item list-group-item-action"
                                onclick="selectCustomer({{ $c['id'] }}, '{{ $c['name'] }}')">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $c['name'] }}</h6>
                                </div>
                                <small class="text-muted">{{ $c['phone'] }} • {{ $c['email'] }}</small>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Held Bills Modal -->
    <div class="modal fade" id="heldBillsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Held Bills</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="heldBillsList">
                        <!-- Held bills will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History Modal -->
    <div class="modal fade" id="orderHistoryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="searchHistory" class="form-control" placeholder="Search orders...">
                    </div>
                    <div id="orderHistoryList">
                        <!-- Order history will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kitchen Orders Modal -->
    <div class="modal fade" id="kitchenOrdersModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kitchen Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="kitchenOrdersList">
                        <!-- Kitchen orders will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bill Details Modal -->
    <div class="modal fade" id="billDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bill Details - Order #<span id="billOrderId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="billDetailsContent">
                        <!-- Bill details will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="printBill()">
                        <i class="fas fa-print"></i> Print Bill
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/admin/js/pos.js') }}"></script>
@endsection
