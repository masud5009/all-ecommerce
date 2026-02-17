@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Stedfast') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Orders') }}</a>
            </li>
        </ol>
    </nav>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6 col-sm-6">
                        <div class="card-title">
                            <h5>{{ __('Stedfast Orders') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 d-flex justify-content-end gap-2 align-items-center">
                        <form action="{{ route('admin.stedfast.balance') }}" method="post" class="d-inline">
                            @csrf
                            <button class="btn btn-secondary btn-sm" type="submit">
                                <i class="fas fa-wallet"></i> {{ __('Check Balance') }}
                            </button>
                        </form>
                        @if (session()->has('stedfast_balance'))
                            <span class="badge bg-info">{{ __('Balance') }}: {{ session('stedfast_balance') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form method="get" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-4">
                            <label class="form-label">{{ __('Search') }}</label>
                            <input type="text" name="q" class="form-control"
                                value="{{ request('q') }}"
                                placeholder="{{ __('Order, customer, phone, tracking') }}">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label">{{ __('Status') }}</label>
                            <select name="status" class="form-select">
                                <option value="">{{ __('All') }}</option>
                                <option value="success" @selected(request('status') === 'success')>{{ __('success') }}</option>
                                <option value="error" @selected(request('status') === 'error')>{{ __('error') }}</option>
                                <option value="skipped" @selected(request('status') === 'skipped')>{{ __('skipped') }}</option>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label d-block">{{ __('Only Stedfast Orders') }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="only_stedfast" value="1"
                                    id="only_stedfast" @checked(request('only_stedfast'))>
                                <label class="form-check-label" for="only_stedfast">
                                    {{ __('Show orders with Stedfast data') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('Filter') }}</button>
                            <a href="{{ route('admin.stedfast.index') }}" class="btn btn-light btn-sm">{{ __('Reset') }}</a>
                        </div>
                    </div>
                </form>

                @if (count($orders) == 0)
                    <h5 class="text-center">{{ __('NO ORDERS FOUND') }} !</h5>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <th scope="col">{{ __('Order') }}</th>
                                <th scope="col">{{ __('Customer') }}</th>
                                <th scope="col">{{ __('Phone') }}</th>
                                <th scope="col">{{ __('Consignment ID') }}</th>
                                <th scope="col">{{ __('Tracking Code') }}</th>
                                <th scope="col">{{ __('Stedfast Status') }}</th>
                                <th scope="col">{{ __('Message') }}</th>
                                <th scope="col">{{ __('Actions') }}</th>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.stedfast.show', $order->id) }}">
                                                {{ '#' . $order->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $order->billing_name ?? __('Guest') }}</td>
                                        <td>{{ $order->billing_phone ?? '-' }}</td>
                                        <td>{{ $order->stedfast_consignment_id ?? '-' }}</td>
                                        <td>{{ $order->stedfast_tracking_code ?? '-' }}</td>
                                        <td>
                                            @php
                                                $status = strtolower((string) $order->stedfast_status);
                                                $badge = 'secondary';
                                                if ($status === 'success') {
                                                    $badge = 'success';
                                                } elseif ($status === 'error') {
                                                    $badge = 'danger';
                                                } elseif ($status === 'skipped') {
                                                    $badge = 'warning';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $badge }}">{{ $order->stedfast_status ?? '-' }}</span>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($order->stedfast_message ?? '-', 40) }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a class="btn btn-info btn-sm"
                                                    href="{{ route('admin.stedfast.show', $order->id) }}">
                                                    {{ __('Details') }}
                                                </a>
                                                <form action="{{ route('admin.stedfast.refresh', $order->id) }}"
                                                    method="post" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-primary btn-sm" type="submit">
                                                        {{ __('Refresh') }}
                                                    </button>
                                                </form>
                                                @if (empty($order->stedfast_consignment_id) && empty($order->stedfast_tracking_code))
                                                    <form action="{{ route('admin.stedfast.create', $order->id) }}"
                                                        method="post" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm" type="submit">
                                                            {{ __('Create') }}
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
