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
                        <button class="btn btn-secondary ms-2" id="earningBtn">{{ __('Filter') }}</button>
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
                        <button class="btn btn-secondary ms-2" id="orderBtn">{{ __('Filter') }}</button>
                    </form>
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
