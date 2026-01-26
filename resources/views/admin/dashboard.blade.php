@extends('admin.layout')
@section('content')
    <h5 class="mb-4">Hi, {{ Auth::guard('admin')->user()->name }}</h5>
    <div class="row">
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ currency_symbol($total_earning) }}</h3>
                        <span class="title">{{ __('Total Earning') }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fas fa-wallet icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ currency_symbol($total_daily_earning) }}</h3>
                        <span class="title">{{ __("Today's Earning") }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fas fa-coins icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ $order_count->daily_total_orders }}</h3>
                        <span class="title">{{ __("Today's Sales") }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fa fa-chart-line icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ $order_count->today_pending_order }}</h3>
                        <span class="title">{{ __("Today's Pending") }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fas fa-clipboard-list icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ $order_count->total_orders }}</h3>
                        <span class="title">{{ __('Total Sales') }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fa fa-shopping-cart icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ $order_count->total_order_completed }}</h3>
                        <span class="title">{{ __('Completed Orders') }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fa fa-check-circle icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ $order_count->total_order_pending }}</h3>
                        <span class="title">{{ __('Pending Orders') }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fa fa-clock icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 mb-3">
            <div class="card card-body">
                <div class="media">
                    <div class="media-body">
                        <h3 class="count_number">{{ $order_count->total_order_rejected }}</h3>
                        <span class="title">{{ __('Rejected Orders') }}</span>
                    </div>

                    <div class="ml-3 align-self-center">
                        <i class="fa fa-times icon-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-xl-6 mb-3">
            <div class="card card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="count_number daily_earning">{{ currency_symbol($total_daily_earning) }}</h3>
                        <span class="title">{{ __('Total Earning on') }} (<span
                                class="earn_date">{{ now()->format('Y-m-d') }}</span>)</span>
                    </div>

                    <form action="{{ route('admin.searchEarning') }}" class="d-flex align-items-center" id="earningForm"
                        method="get">
                        <input type="text" name="date" class="flatpickr form-control" placeholder="Select Date">
                        <button class="btn btn-secondary ms-2" id="earningBtn">{{__('Filter')}}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-xl-6 mb-3">
            <div class="card card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="count_number daily_order">{{ $order_count->daily_total_orders }}</h3>
                        <span class="title">{{ __('Total Sales on') }} (<span
                                class="order_date">{{ now()->format('Y-m-d') }}</span>)</span>
                    </div>

                    <form action="{{ route('admin.searchOrders') }}" class="d-flex align-items-center" id="orderForm"
                        method="get">
                        <input type="text" name="date" class="flatpickr form-control" placeholder="Select Date">
                        <button class="btn btn-secondary ms-2" id="orderBtn">{{__('Filter')}}</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Translation API Usage Widget -->
<div class="col-sm-12 col-xl-6 mb-3">
    <div class="card card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="card-title mb-0">
                <i class="fas fa-language me-2"></i>Translation API Usage
            </h6>
            <small class="text-muted">Since: {{ date('M j, Y', strtotime($apiStats['installation_date'])) }}</small>
        </div>

        <!-- Total Statistics -->
        <div class="row text-center mb-4">
            <div class="col-4">
                <h4 class="text-primary mb-1">{{ $apiStats['total_requests'] }}</h4>
                <small class="text-muted">Total Calls</small>
            </div>
            <div class="col-4">
                <h4 class="text-info mb-1">{{ number_format($apiStats['total_chars']) }}</h4>
                <small class="text-muted">Characters</small>
            </div>
            <div class="col-4">
                <h4 class="text-success mb-1">{{ $apiStats['success_rate'] }}%</h4>
                <small class="text-muted">Success Rate</small>
            </div>
        </div>

        <!-- API Usage Breakdown -->
        <div class="mb-3">
            <small class="text-muted d-block mb-2">API Usage Breakdown:</small>
            <div class="row text-center">
                <div class="col-4">
                    <div class="border rounded p-2">
                        <small class="fw-bold text-success d-block">Google</small>
                        <small class="text-muted">{{ $apiStats['google_requests'] }} calls</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-2">
                        <small class="fw-bold text-info d-block">MyMemory</small>
                        <small class="text-muted">{{ $apiStats['mymemory_requests'] }} calls</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border rounded p-2">
                        <small class="fw-bold text-warning d-block">Libre</small>
                        <small class="text-muted">{{ $apiStats['libre_requests'] }} calls</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Limits Info -->
        <div class="mt-3 pt-3 border-top">
            <small class="text-muted d-block mb-2">API Limits Information:</small>
            <div class="row">
                <div class="col-12">
                    <small class="d-block mb-1">
                        <span class="fw-bold">Google:</span>
                        {{ number_format($apiStats['api_limits']['google']['monthly_chars']) }} chars/month
                    </small>
                    <small class="d-block mb-1">
                        <span class="fw-bold">MyMemory:</span>
                        {{ number_format($apiStats['api_limits']['mymemory']['daily_requests']) }} requests/day
                    </small>
                    <small class="d-block">
                        <span class="fw-bold">LibreTranslate:</span>
                        {{ number_format($apiStats['api_limits']['libre']['daily_requests']) }} requests/day
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

        <div class="pt-3">
            <div class="row">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card shadow px-3 py-3">
                        <canvas id="saleChart"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card shadow px-3 py-3">
                        <canvas id="userChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        "use strict";
        const months = @php echo json_encode($months) @endphp;
        const monthlyIncome = @php echo json_encode($monthlyIncome) @endphp;
        const monthlyOrder = @php echo json_encode($monthlyOrder) @endphp;
        const maxMonth = {{ $maxMonth }};
        const minMonth = {{ $minMonth }};
        const maxOrder = {{ $maxOrder }};
        const minOrder = {{ $minOrder }};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/admin/js/chart.js') }}"></script>
    <script src="{{ asset('assets/admin/js/dashbaord_search.js') }}"></script>
@endsection
