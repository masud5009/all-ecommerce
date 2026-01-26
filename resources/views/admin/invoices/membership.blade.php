<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <link rel="stylesheet" href="{{ asset('assets/front/css/membership-pdf.css') }}">
</head>

<body>
    <div class="main">
        <table class="heading">
            <tr>
                <td>
                    @if ($websiteInfo['website_logo'])
                        <img src="{{ asset('assets/front/img/' . $websiteInfo['website_logo']) }}" height="40"
                            class="d-inline-block">
                    @else
                        <img src="{{ asset('assets/admin/img/noimage.jpg') }}" height="40" class="d-inline-block">
                    @endif
                </td>
                <td class="text-right strong invoice-heading">INVOICE</td>
            </tr>
        </table>
        <div class="header">
            <div class="ml-20">
                <table class="text-left">
                    <tr>
                        <td class="strong small gry-color">Bill to:</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Username: </strong>{{ $vendor['username'] }}</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Email: </strong> {{ $vendor['email'] }}</td>
                    </tr>
                    @if (array_key_exists('phone', $vendor))
                        <tr>
                            <td class="gry-color small"><strong>Phone: </strong> {{ $vendor['phone'] }}</td>
                        </tr>
                    @endif
                </table>
            </div>
            <div class="order-details">
                <table class="text-right">
                    <tr>
                        <td class="strong">Order Details:</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Order ID:</strong> #{{ $membership['transaction_id'] }}</td>
                    </tr>
                    @if (array_key_exists('discount', $membership))
                        @if ($membership['discount'] > 0)
                            <tr>
                                <td class="gry-color small"><strong>Package Price:</strong>
                                    {{ $membership['package_price'] == 0 ? 'Free' : $membership['package_price'] }}</td>
                            </tr>
                            <tr>
                                <td class="gry-color small"><strong>Discount:</strong> -<span
                                        class="text-success">{{ $membership['discount'] }}</span></td>
                            </tr>
                        @endif
                    @endif
                    <tr>
                        <td class="gry-color small"><strong>Total:</strong> {{ $membership['price'] }}</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Payment Method:</strong>
                            {{ $membership['payment_method'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Payment Status:</strong>Completed</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Order Date:</strong>
                            {{ \Illuminate\Support\Carbon::now()->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="package-info">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="gry-color info-titles">
                        <th width="20%">Package Title</th>
                        <th width="20%">Start Date</th>
                        <th width="20%">Expire Date</th>
                        <th width="20%">Currency</th>
                        <th width="20%">Total</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    <tr class="text-center">
                        <td>{{ $package['title'] }}</td>
                        <td>{{ $membership['start_date'] }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($membership['expire_date'])->format('Y') == '9999' ? 'Lifetime' : $membership['expire_date'] }}
                        </td>
                        <td>{{ $membership['currency'] }}</td>
                        <td>
                            {{ $membership['price'] == 0 ? 'Free' : $membership['price'] }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <table class="mt-80">
            <tr>
                <td class="text-right regards">Thanks & Regards,</td>
            </tr>
            <tr>
                <td class="text-right strong regards">{{ $websiteInfo['website_title'] }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
