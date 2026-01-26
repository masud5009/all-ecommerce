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
                <a href="#">{{ __('All Sales') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <x-bulk-delete :url="route('admin.sales.bulkDelete')" itemTextName="sales" />
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-8 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('All Sales') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($orders) == 0)
                        <h5 class="text-center">{{ __('NO SALES FOUND') }} !</h5>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <th scope="col">
                                        <input type="checkbox" class="bulk-check" data-val="all">
                                    </th>
                                    <th scope="col">{{ __('Order Code') }}</th>
                                    <th scope="col">{{ __('Customer') }}</th>
                                    <th scope="col">{{ __('Payment method') }}</th>
                                    <th scope="col">{{ __('Payment Status') }}</th>
                                    <th scope="col">{{ __('Order Status') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $order)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="bulk-check" data-val="{{ $order->id }}">
                                            </td>
                                            <td>
                                                {{ '#' . $order->order_number }}
                                            </td>
                                            <td>
                                                @if (empty($order->billing_name))
                                                    {{ __('Guest') }}
                                                @else
                                                    {{ $order->billing_name }}
                                                @endif
                                            </td>
                                            <td>
                                                {{ $order->payment_method }}
                                            </td>
                                            <td>
                                                    <span class="badge bg-{{ $order->payment_status == 'completed' ? 'success' : 'danger' }}">{{ __($order->payment_status) }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $order->order_status == 'completed' ? 'success' : 'danger' }}">{{ __($order->order_status) }}</span>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-info btn-sm dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Select
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.sales.details', ['id' => $order->id]) }}">
                                                            {{ __('Details') }}</a>
                                                        <a class="dropdown-item" target="_blank"
                                                            href="{{ asset('assets/front/invoices/product/' . $order->invoice_number) }}">
                                                            {{ __('Invoice') }}</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.sales.details', ['id' => $order->id]) }}">
                                                            {{ __('receipt') }}</a>

                                                        <form class="deleteForm" action="{{ route('admin.sales.delete') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" value="{{ $order->id }}"
                                                                name="order_id">
                                                            <a class="dropdown-item deleteBtn" type="button">
                                                                {{ __('Delete') }}
                                                            </a>
                                                        </form>

                                                    </div>
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
