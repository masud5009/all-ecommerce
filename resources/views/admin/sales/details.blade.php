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
                <a href="{{ route('admin.sales', ['language' => $defaultLang->code]) }}">{{ __('All Sales') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ '#' . $order->order_number }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">{{ __('Details') }}</a>
            </li>
        </ol>
    </nav>

    @php
        $symbol = $order->currency_symbol;
        $position = $order->currency_symbol_position;
    @endphp
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card order-details-div">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card-title details-header">
                                    <p class="m-0 fw-bold">{{ __('Order') }} {{ '#' . $order->order_number }}</p>
                                    <p>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y h:i A') }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6 d-flex justify-content-start justify-content-lg-end align-items-baseline">
                                <a href="{{ asset('assets/front/invoices/product/' . $order->invoice_number) }}" class="btn btn-primary" target="_blank" download=""><i class="fa-solid fa-receipt"></i> {{ __('Download Invoice') }}</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="payment-information">
                            @if (!is_null($order->delivery_date))
                                <div class="row mb-2">
                                    <div class="col-lg-6">
                                        <strong>{{ __('Estimated Delivery Date') . ' :' }}</strong>
                                    </div>

                                    <div class="col-lg-6">
                                        {{ \Carbon\Carbon::parse($order->delivery_date)->format('jS M, Y') }}
                                    </div>
                                </div>
                            @endif
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Subtotal') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ currency_symbol_order($order->cart_total ?? 0, $symbol ?? '', $position ?? 'left') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Shipping Charge') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">
                                    {{ currency_symbol_order($order->shipping_charge ?? 0, $symbol ?? '', $position ?? 'left') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Discount') . ' (%) :' }}</strong>
                                </div>

                                <div class="col-lg-6">
                                    {{ currency_symbol_order($order->discount_amount ?? 0, $symbol ?? '', $position ?? 'left') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Tax') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ currency_symbol_order($order->tax ?? 0, $symbol ?? '', $position ?? 'left') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Total Amount') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ currency_symbol_order($order->pay_amount ?? 0, $symbol ?? '', $position ?? 'left') }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Gateway Type') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ $order->gateway }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Payment Method') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ $order->payment_method }}
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Payment Status') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">
                                    @if ($order->payment_status == 'pending')
                                        <span class="badge bg-warning">{{ __('Pending') }}</span>
                                    @elseif ($order->payment_status == 'rejected')
                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @elseif ($order->payment_status == 'completed')
                                        <span class="badge bg-success">{{ __('Completed') }}</span>
                                    @else
                                        {{ $order->payment_status }}
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Order Status') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">
                                    @if ($order->order_status == 'pending')
                                        <span class="badge bg-warning">{{ __('Pending') }}</span>
                                    @elseif ($order->order_status == 'rejected')
                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @elseif ($order->order_status == 'completed')
                                        <span class="badge bg-success">{{ __('Completed') }}</span>
                                    @else
                                        {{ $order->order_status }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title d-inline-block">{{ __('Customer Information') }}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="payment-information">
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Name') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ $order->billing_name ?? __('Guest') }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Email') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ $order->billing_email ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Phone') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ $order->billing_phone ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Address') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ $order->billing_address ?? '-' }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-lg-6">
                                    <strong>{{ __('Shipping Address') . ' :' }}</strong>
                                </div>

                                <div class="col-lg-6">{{ $order->shipping_address ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title d-inline-block">
                            {{ __('Order Products') }}
                        </div>
                    </div>

                    <div class="card-body">
                        @if (count($order_items) == 0)
                            <h3 class="text-center mt-2">{{ __('NO PRODUCT FOUND') . '!' }}</h3>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped mt-3" id="basic-datatables">
                                    <thead>
                                        <tr>
                                            <th scope="col">{{ __('SL') }}</th>
                                            <th scope="col">{{ __('Product') }}</th>
                                            <th scope="col">{{ __('Qty') }}</th>
                                            <th scope="col">{{ __('Price') }}</th>
                                            <th scope="col">{{ __('Line Total') }}</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $itemsGrandTotal = $order_items->sum(function ($orderItem) {
                                            return ($orderItem->product_price ?? 0) * ((int) $orderItem->qty);
                                        });
                                    @endphp
                                    <tbody>

                                        @foreach ($order_items as $item)
                                            @php
                                                $productTitle = optional($item->product->content->first())->title
                                                    ?? __('Unknown Product');
                                                $variations = json_decode($item->variations);
                                                $variantLabel = null;
                                                if ($item->variant) {
                                                    $parts = $item->variant->variantValues
                                                        ->sortBy(function ($variantValue) {
                                                            return optional(optional($variantValue->optionValue)->option)->position ?? 0;
                                                        })
                                                        ->map(function ($variantValue) {
                                                            $option = optional($variantValue->optionValue)->option;
                                                            $value = optional($variantValue->optionValue)->value;
                                                            if (!$option || $value === null) {
                                                                return null;
                                                            }
                                                            return $option->name . ': ' . $value;
                                                        })
                                                        ->filter()
                                                        ->values();
                                                    $variantLabel = $parts->isNotEmpty() ? $parts->implode(', ') : __('Default');
                                                }
                                                $qty = (int) $item->qty;
                                                $lineTotal = ($item->product_price ?? 0) * $qty;
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    {{ truncateString($productTitle, 25) }}
                                                    @if ($item->variant)
                                                        <div class="small text-muted">
                                                            {{ __('Variant') }}: {{ $variantLabel }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            {{ __('SKU') }}: {{ $item->variant->sku ?? 'N/A' }}
                                                        </div>
                                                    @endif
                                                    @if ($item->soldSerials->isNotEmpty())
                                                        <div class="small text-muted">
                                                            {{ __('Serials') }}:
                                                            {{ $item->soldSerials->pluck('serial')->implode(', ') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>{{ $qty }}</td>
                                                <td>
                                                    <div class="py-3">
                                                        <span>{{ __('Product Price') }}</span>:
                                                        {{ $order->currency_symbol . $item->product_price }}
                                                        @if (!empty($variations))
                                                            <br>
                                                            {{ __('Variations') }} :
                                                            <br>
                                                            @foreach ($variations as $k => $vitm)
                                                                @php
                                                                    $variationName = data_get($vitm, 'variation_name')
                                                                        ?? data_get($vitm, 'name')
                                                                        ?? data_get($vitm, 'variation')
                                                                        ?? __('Option');
                                                                    $optionName = data_get($vitm, 'option_name')
                                                                        ?? data_get($vitm, 'value')
                                                                        ?? data_get($vitm, 'option')
                                                                        ?? __('N/A');
                                                                    $variationPrice = data_get($vitm, 'price') ?? 0;
                                                                @endphp
                                                                <span>
                                                                    <span>{{ $variationName }}:</span>
                                                                    <span class="gry-color">{{ $optionName }} -
                                                                        {{ $order->currency_symbol . $variationPrice }}</span>
                                                                </span>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ currency_symbol_order($lineTotal ?? 0, $symbol ?? '', $position ?? 'left') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">{{ __('Total') }}:</td>
                                            <td class="fw-bold">{{ currency_symbol_order($itemsGrandTotal ?? 0, $symbol ?? '', $position ?? 'left') }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
