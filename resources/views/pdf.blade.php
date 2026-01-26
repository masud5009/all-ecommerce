<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
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
        * {
            margin: 0;
            padding: 0;
            line-height: 1.5;
            font-family: "{{ $font_family }}" !important;
            color: "{{ $color }}";
        }

        body {
            font-family: "{{ $font_family }}" !important;
        }

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
    </style>
</head>

<body>
    <div class="main">
        <table class="heading" style="display: flex">
            <tr>
                <td>
                    <img src="{{ asset('assets/admin/noimage.jpg') }}" height="100" class="d-inline-block">
                </td>
                <td class="text-right strong invoice-heading">INVOICE</td>
            </tr>
        </table>
        <div class="header">
            <div class="ml-20">
                <table>
                    <tr>
                        <td class="strong">Bill to:</td>
                    </tr>
                    <tr>
                        <td>
                            <span>Masud Rana</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>1234567789</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>masud@gmail.com</span>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>dhaka</span>

                        </td>
                    </tr>
                </table>
            </div>
            <div class="order-details">
                <table>
                    <tr>
                        <td class="strong">Order Details:</td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Order Number: </strong> #
                            sdjlsd897asd9f</td>
                    </tr>

                    <tr>
                        <td class="gry-color small"><strong>Discount: </strong>
                            $34 </td>
                    </tr>

                    <tr>
                        <td class="gry-color small"><strong>Tax: </strong>
                            $dsff</td>
                    </tr>

                    <tr>
                        <td class="gry-color small"><strong>Total Price: </strong>
                            $454 </td>
                    </tr>

                    <tr>
                        <td class="gry-color small"><strong>Payment Method: </strong>
                            Cash</td>
                    </tr>
                    <tr>
                        <td class="gry-color small">
                            <strong>Payment Status: </strong>completed
                        </td>
                    </tr>
                    <tr>
                        <td class="gry-color small"><strong>Order Date: </strong>
                            10/20/10</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="package-info">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="text-dark info-titles">
                        <th class="w_45">Title</th>
                        <th class="w_10">Quantity</th>
                        <th class="w_45">Price</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    <tr class="text-center">
                        <td>
                            <span>product title</span>
                        </td>
                        <td>12</td>
                        <td>
                            <span>Product Price</span>:
                            $454
                            <br>
                            Variations :
                            <br>
                            <span>
                                <span>Color:</span>
                                <span class="gry-color">White - $34</span>
                            </span>
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
                <td class="text-right strong regards">
                    <span>myapp</span>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
