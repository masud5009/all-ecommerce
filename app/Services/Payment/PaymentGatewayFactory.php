<?php

namespace App\Services\Payment;

use InvalidArgumentException;

class PaymentGatewayFactory
{
    public static function supportedGateways(): array
    {
        return array_keys(self::gatewayMap());
    }

    public static function make(string $gateway): PaymentGatewayInterface
    {
        $map = self::gatewayMap();

        if (! isset($map[$gateway])) {
            throw new InvalidArgumentException("Unsupported payment gateway: {$gateway}");
        }

        return app($map[$gateway]);
    }

    private static function gatewayMap(): array
    {
        return [
            'stripe' => \App\Services\Payment\Gateways\StripeGateway::class,
            'paypal' => \App\Services\Payment\Gateways\PayPalGateway::class,
            'sslcommerz' => \App\Services\Payment\Gateways\SslCommerzGateway::class,
        ];
    }
}
