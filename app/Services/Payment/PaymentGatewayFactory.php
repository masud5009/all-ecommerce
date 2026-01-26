<?php

namespace App\Services\Payment;

use InvalidArgumentException;

class PaymentGatewayFactory
{
    public static function make(string $gateway): PaymentGatewayInterface
    {
        $map = [
            'stripe'      => \App\Services\Payment\Gateways\StripeGateway::class,
            'paypal'      => \App\Services\Payment\Gateways\PayPalGateway::class,
        ];

        if (! isset($map[$gateway])) {
            throw new InvalidArgumentException("Unsupported payment gateway: {$gateway}");
        }

        return app($map[$gateway]);
    }
}
