@extends('admin.layout')
@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}"><span class="fas fa-home"></span></a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.stedfast.index') }}">{{ __('Stedfast') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Order') }} {{ '#' . $order->order_number }}</a>
            </li>
        </ol>
    </nav>

    @php
        $payloadView = $order->stedfast_payload;
        if ($payloadView) {
            $decoded = json_decode($payloadView, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $payloadView = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        $responseView = $order->stedfast_response;
        if ($responseView) {
            $decoded = json_decode($responseView, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $responseView = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
    @endphp

    <div class="col-lg-12">
        <div class="card mb-3">
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card-title">
                            <h5>{{ __('Stedfast Order Details') }}</h5>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.stedfast.index') }}" class="btn btn-light btn-sm">
                            {{ __('Back') }}
                        </a>
                        <form action="{{ route('admin.stedfast.refresh', $order->id) }}" method="post" class="d-inline">
                            @csrf
                            <button class="btn btn-primary btn-sm" type="submit">{{ __('Refresh') }}</button>
                        </form>
                        @if (empty($order->stedfast_consignment_id) && empty($order->stedfast_tracking_code))
                            <form action="{{ route('admin.stedfast.create', $order->id) }}" method="post"
                                class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm" type="submit">{{ __('Create') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Order') }}:</strong></div>
                    <div class="col-lg-9">
                        <a href="{{ route('admin.sales.details', ['id' => $order->id]) }}">
                            {{ '#' . $order->order_number }}
                        </a>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Customer') }}:</strong></div>
                    <div class="col-lg-9">{{ $order->billing_name ?? __('Guest') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Phone') }}:</strong></div>
                    <div class="col-lg-9">{{ $order->billing_phone ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Consignment ID') }}:</strong></div>
                    <div class="col-lg-9">{{ $order->stedfast_consignment_id ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Tracking Code') }}:</strong></div>
                    <div class="col-lg-9">{{ $order->stedfast_tracking_code ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Stedfast Status') }}:</strong></div>
                    <div class="col-lg-9">{{ $order->stedfast_status ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Message') }}:</strong></div>
                    <div class="col-lg-9">{{ $order->stedfast_message ?? '-' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-3"><strong>{{ __('Last Updated') }}:</strong></div>
                    <div class="col-lg-9">{{ $order->updated_at }}</div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5>{{ __('Last Payload') }}</h5>
            </div>
            <div class="card-body">
                <pre class="mb-0">{{ $payloadView ?? '-' }}</pre>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>{{ __('Last Response') }}</h5>
            </div>
            <div class="card-body">
                <pre class="mb-0">{{ $responseView ?? '-' }}</pre>
            </div>
        </div>
    </div>
@endsection
