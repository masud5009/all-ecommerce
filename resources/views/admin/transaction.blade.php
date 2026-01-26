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
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card-title">
                            <h5>{{ __('All Transactions') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-4">
                    </div>
                    <div class="col-lg-4">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-lg-12 mx-auto">
                    @if (count($transactions) == 0)
                        <h5 class="text-center">{{ __('NO TRANSACTIONS FOUND') }} !</h5>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <th scope="col">{{ __('Transaction Id') }}</th>
                                    <th scope="col">{{ __('Date') }}</th>
                                    <th scope="col">{{ __('Transaction Type') }}</th>
                                    <th scope="col">{{ __('Payment Method') }}</th>
                                    <th scope="col">{{ __('Pre Balance') }}</th>
                                    <th scope="col">{{ __('Add Balance') }}</th>
                                    <th scope="col">{{ __('After Balance') }}</th>
                                    <th scope="col">{{ __('Status') }}</th>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $transaction)
                                        @php
                                            $symbol = $transaction->currency_symbol;
                                            $position = $transaction->currency_symbol_position;
                                        @endphp
                                        <tr>
                                            <td>{{ '#' . $transaction->transaction_id }}</td>
                                            <td>{{ $transaction->created_at }}</td>
                                            <td>{{ str_replace('_', ' ', ucwords($transaction->transaction_type)) }}</td>
                                            <td>{{ $transaction->payment_method }}</td>
                                            <td>{{ currency_symbol_order($transaction->pre_balance, $symbol, $position) }}
                                            </td>
                                            <td>
                                                <span class="text-success">(+)</span>
                                                {{ currency_symbol_order($transaction->actual_total, $symbol, $position) }}
                                            </td>
                                            <td>
                                                {{ currency_symbol_order($transaction->after_balance, $symbol, $position) }}
                                            </td>
                                            <td>
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
