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
                <a href="#">{{ __('Reports') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card shadow">
            <x-bulk-delete :url="route('admin.sales.bulkDelete')" itemTextName="sales" />
            <div class="card-header">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3 mb-lg-0">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#filterModal">
                                <i class="fas fa-search"></i> {{ __('Search & Export Report') }}
                            </button>
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3 mb-lg-0 d-flex">
                            @if (count($orders) > 0)
                                {{ $orders->links() }}
                            @endif
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-3 mb-lg-0">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.sales.exportReport', ['language' => request()->input('language')]) }}"
                                    class="btn btn-success btn-sm me-2 card-header-button">
                                    <i class="fas fa-file-export"></i> {{ __('Export CSV') }}
                                </a>
                                <a href="{{ route('admin.sales.exportReportExcel', ['language' => request()->input('language')]) }}"
                                    class="btn btn-success btn-sm card-header-button">
                                    <i class="fas fa-file-export"></i> {{ __('Export Excel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($orders) == 0)
                        <h5 class="text-center">{{ __('NO DATA FOUND') }} !</h5>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <th scope="col">{{ __('SL.') }}</th>
                                    <th scope="col">{{ __('Order Code') }}</th>
                                    <th scope="col">{{ __('Billing Name') }}</th>
                                    <th scope="col">{{ __('Billing Email') }}</th>
                                    <th scope="col">{{ __('Billing Phone') }}</th>
                                    <th scope="col">{{ __('Billing City') }}</th>
                                    <th scope="col">{{ __('Billing Address') }}</th>
                                    <th scope="col">{{ __('Shipping Address') }}</th>
                                    <th scope="col">{{ __('Gateway') }}</th>
                                    <th scope="col">{{ __('Payment Method') }}</th>
                                    <th scope="col">{{ __('Payment Status') }}</th>
                                    <th scope="col">{{ __('Order Status') }}</th>
                                    <th scope="col">{{ __('Cart Total') }}</th>
                                    <th scope="col">{{ __('Discount') }}</th>
                                    <th scope="col">{{ __('Tax') }}</th>
                                    <th scope="col">{{ __('Shipping Charge') }}</th>
                                    <th scope="col">{{ __('Total') }}</th>
                                    <th scope="col">{{ __('Shipping Date') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ '#' . $order->order_number }}</td>
                                            <td>{{ $order->billing_name ?? __('Guest') }}</td>
                                            <td>{{ $order->billing_email ?? '-' }}</td>
                                            <td>{{ $order->billing_phone ?? '-' }}</td>
                                            <td>{{ $order->billing_city ?? '-' }}</td>
                                            <td>{{ $order->billing_address ?? '-' }}</td>
                                            <td>{{ $order->shipping_address ?? '-' }}</td>
                                            <td>{{ $order->gateway }}</td>
                                            <td>{{ $order->payment_method }}</td>
                                            <td>
                                                @if ($order->payment_status == 'completed')
                                                    <span class="badge bg-success">{{ $order->payment_status }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $order->payment_status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->order_status == 'completed')
                                                    <span class="badge bg-success">{{ $order->order_status }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $order->order_status }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $order->cart_total }}</td>
                                            <td>{{ $order->discount_amount }}</td>
                                            <td>{{ $order->tax }}</td>
                                            <td>{{ $order->shipping_charge }}</td>
                                            <td>{{ $order->pay_amount }}</td>
                                            <td>{{ $order->delivery_date ?? '-' }}</td>
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

    <!-- Modal -->
    <div class="modal fade" id="filterModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-top">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="searchForm">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ __('From') }}</label>
                                    <input type="text" name="from_date" class="form-control flatpickr"
                                        value="{{ request()->input('from_date') }}"
                                        placeholder="{{ __('Select start date') }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ __('To') }}</label>
                                    <input type="text" name="to_date" class="form-control flatpickr"
                                        value="{{ request()->input('to_date') }}"
                                        placeholder="{{ __('Select end date') }}">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">{{ __('Order Status') }}</label>
                                    <select name="order_status" class="form-select">
                                        <option selected value="">{{ __('All') }}</option>
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="rejected">{{ __('Rejected') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">{{ __('Payment Status') }}</label>
                                    <select name="payment_status" class="form-select">
                                        <option selected value="">{{ __('All') }}</option>
                                        <option value="pending">{{ __('Pending') }}</option>
                                        <option value="completed">{{ __('Completed') }}</option>
                                        <option value="rejected">{{ __('Rejected') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">{{ __('Payment Method') }}</label>
                                    <select name="payment_method" class="form-select">
                                        <option selected value="">{{ __('All') }}</option>
                                        <option value="cash">{{ __('Cash') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="searchForm">{{ __('Search Now') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
