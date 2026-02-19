@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Transactions') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card transactions-card">
            <div class="card-header transactions-card-header">
                <div class="card-title mb-0">
                    <h5 class="mb-0">{{ __('All Transactions') }}</h5>
                </div>
                <div class="transactions-pagination">
                    {{ $transactions->links() }}
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($transactions) == 0)
                        <h5 class="text-center">{{ __('NO TRANSACTIONS FOUND') }} !</h5>
                    @else
                        <div class="table-responsive transactions-table-wrapper">
                            <table class="table table-striped align-middle transactions-table">
                                <thead>
                                    <th scope="col" class="text-nowrap">{{ __('Transaction Id') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('Date') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('Transaction Type') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('Payment Method') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('Pre Balance') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('Add Balance') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('After Balance') }}</th>
                                    <th scope="col" class="text-nowrap">{{ __('Status') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        @php
                                            $symbol = $transaction->currency_symbol;
                                            $position = $transaction->currency_symbol_position;
                                        @endphp
                                        <tr>
                                            <td class="text-nowrap">{{ '#' . $transaction->transaction_id }}</td>
                                            <td class="text-nowrap">{{ $transaction->created_at }}</td>
                                            <td>{{ str_replace('_', ' ', ucwords($transaction->transaction_type)) }}</td>
                                            <td class="text-nowrap">{{ $transaction->payment_method }}</td>
                                            <td class="text-nowrap">{{ currency_symbol_order($transaction->pre_balance, $symbol, $position) }}
                                            </td>
                                            <td class="text-nowrap">
                                                <span class="text-success">(+)</span>
                                                {{ currency_symbol_order($transaction->actual_total, $symbol, $position) }}
                                            </td>
                                            <td class="text-nowrap">
                                                {{ currency_symbol_order($transaction->after_balance, $symbol, $position) }}
                                            </td>
                                            <td class="text-nowrap">
                                                @if ($transaction->transaction_type == 'product_purchase')
                                                    <span class="badge bg-success">{{ __('Paid') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                                                @endif
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
