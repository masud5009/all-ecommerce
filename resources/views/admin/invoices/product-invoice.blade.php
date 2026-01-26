<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Invoice') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/front/css/product-pdf.css') }}">
    @php
        $font_family = 'DejaVu Sans, serif';
        $color = '#333542';
        $rtl = 'rtl';
        $unicode_bidi = 'bidi-override';
        $di_block = 'inline-block';
        $w_60 = '60%';
        $w_10 = '10%';
        $w_30 = '30%';
        $w_80 = '80%';
        $w_20 = '20%';
        $w_45 = '45%';
    @endphp
    <style>
        .rtl {
            unicode-bidi: "{{ $unicode_bidi }}" !important;
            direction: "{{ $rtl }}" !important;
        }

        span {
            display: "{{ $di_block }}"
        }

        .w_50 {
            width: "{{ $w_60 }}" !important;
        }

        .w_10 {
            width: "{{ $w_10 }}" !important;
        }

        .w_40 {
            width: "{{ $w_30 }}" !important;
        }

        .w_80 {
            width: "{{ $w_80 }}";
        }

        .w-20 {
            width: "{{ $w_20 }}";
        }

        .w_45 {
            width: "{{ $w_45 }}";
        }


        .invoice-header {
            background: rgba({{ hexToRgba($bs->website_color) }}, 0.2);
            padding: 20px 25px;
        }

        .tm_invoice_info_table {
            background: rgba({{ hexToRgba($bs->website_color) }}, 0.2);
        }

        .package-info-table thead {
            background: #{{ $bs->website_color }};
        }

        .bg-primary {
            background: #{{ $bs->website_color }};
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="invoice-container">
            <div class="invoice-wrapper">
                <div class="invoice-area pb-30">

                    <div class="invoice-header clearfix mb-15 px-25">

                        <div class="float-left">
                            @if ($bs->website_logo)
                                <img src="{{ asset('assets/front/img/' . $bs->website_logo) }}" height="40"
                                    class="d-inline-block ">
                            @else
                                <img src="{{ asset('assets/admin/noimage.jpg') }}" height="40"
                                    class="d-inline-block">
                            @endif
                        </div>
                        <div class="text-right strong invoice-heading float-right">{{ __('INVOICE') }}</div>

                    </div>

                    <div class="px-25 mb-15 clearfix tm_invoice_info_table">
                        <table class=" ">
                            <tbody>
                                <tr>
                                    <td>
                                        <span><b>{{ __('Payment Method') }}: </b> {{ $order->payment_method }}</span>
                                    </td>
                                    <td>
                                        <span><b>{{ __('Invoice No') }}:</b> #{{ $order->order_number }} </span>
                                    </td>
                                    <td class="text-right">
                                        <span><b>{{ __('Date') }}:</b>
                                            {{ \Illuminate\Support\Carbon::now()->format('d-m-Y') }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="header clearfix px-25 mb-15">
                        <div class="text-left  float-left">
                            <div class="strong">{{ __('Bill to') }}:</div>
                            <div class="small">
                                <strong>{{ __('Name') }}: </strong>
                                <span>{{ ucfirst($order->billing_name) }}</span>
                            </div>
                            <div class="small">
                                <strong>{{ __('Phone') }}: </strong>
                                <span>{{ ucfirst($order->billing_phone) }}</span>
                            </div>
                            <div class="small">
                                <strong>{{ __('Email') }}: </strong>
                                <span>{{ ucfirst($order->billing_email) }}</span>
                            </div>
                            <div class="small">
                                <strong>{{ __('Address') }}: </strong>
                                <span>{{ ucfirst($order->billing_address) }}</span>
                            </div>

                        </div>
                        <div class="order-details float-right">
                            <div class="text-right">
                                <div class="strong">{{ __('Order Details') }}:</div>
                                {{-- @if (!is_null($order->discount_amount))
                                    <div class=" small"><strong>{{ __('Discount') }}: </strong>
                                        {{ currency_symbol_order($order->discount_amount, $order->currency_symbol, $order->currency_symbol_position) }}
                                    </div>
                                @endif

                                <div class=" small"><strong>{{ __('Tax') }}: </strong>
                                    {{ currency_symbol_order($order->tax, $order->currency_symbol, $order->currency_symbol_position) }}
                                </div> --}}

                                <div class=" small"><strong>{{ __('Paid Amount') }}: </strong>
                                    {{ currency_symbol_order($order->pay_amount, $order->currency_symbol, $order->currency_symbol_position) }}
                                </div>

                                <div class="small">
                                    <strong>{{ __('Payment Status') }}: </strong>{{ $order->payment_status }}
                                </div>
                                <div class="small">
                                    <strong>{{ __('Order Status') }}: </strong>{{ $order->order_status }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="package-info px-25">
                        <table class="text-left package-info-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Title') }}</th>
                                    <th class="tm_border_left text-center">{{ __('Quantity') }}</th>
                                    <th class="tm_border_left text-right">{{ __('Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orderItems as $item)
                                    @php
                                        $productTitle = App\Models\ProductContent::where([
                                            ['product_id', $item->product_id],
                                            ['language_id', $lang],
                                        ])
                                            ->pluck('title')
                                            ->first();
                                        $variations = json_decode($item->variations);
                                    @endphp
                                    <tr>
                                        <td>
                                            <span>{{ truncateString($productTitle, 25) }}</span>
                                        </td>
                                        <td class="tm_border_left text-center">{{ $item->qty }}</td>
                                        <td class="tm_border_left text-right">
                                            {{ $order->currency_symbol . $item->product_price }}
                                            <br>
                                            @if (!empty($variations))
                                                @foreach ($variations as $k => $vitm)
                                                    <span>{{ $vitm->variation_name }}
                                                        <small>({{ $vitm->option_name }})</small>:
                                                        {{ $order->currency_symbol . $vitm->price }}</span>
                                                @endforeach
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="tm_invoice_footer clearfix px-25">
                        <div class="tm_right_footer float-right">
                            <table>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">{{ __('Sub Total') }}</td>
                                        <td class="text-right fw-bold">
                                            {{ currency_symbol_order($order->cart_total, $order->currency_symbol, $order->currency_symbol_position) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Tax') }}</td>
                                        <td class="text-right fw-bold">
                                            {{ currency_symbol_order($order->tax, $order->currency_symbol, $order->currency_symbol_position) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">{{ __('Shipping Charge') }}</td>
                                        <td class="text-right fw-bold">
                                            {{ currency_symbol_order($order->shipping_charge, $order->currency_symbol, $order->currency_symbol_position) }}
                                        </td>
                                    </tr>
                                    <tr class="bg-primary paid-tr">
                                        <td class="fw-bold text-white">{{ __('Paid Amount') }}</td>
                                        <td class="text-right fw-bold text-white">
                                            {{ currency_symbol_order($order->pay_amount, $order->currency_symbol, $order->currency_symbol_position) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="mt-50">
                        <div class="text-right regards">{{ __('Thanks & Regards') }},</div>
                        <div class="text-right strong regards">
                            <span>{{ $bs->website_title }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



</body>

</html>
