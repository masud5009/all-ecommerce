<?php

namespace Tests\Unit;

use App\Services\Payment\Gateways\SslCommerzGateway;
use App\Services\Payment\PaymentGatewayFactory;
use InvalidArgumentException;
use Tests\TestCase;

class PaymentGatewayFactoryTest extends TestCase
{
    public function test_supported_gateways_include_sslcommerz(): void
    {
        $supported = PaymentGatewayFactory::supportedGateways();

        $this->assertContains('sslcommerz', $supported);
    }

    public function test_make_returns_sslcommerz_gateway_instance(): void
    {
        $gateway = PaymentGatewayFactory::make('sslcommerz');

        $this->assertInstanceOf(SslCommerzGateway::class, $gateway);
    }

    public function test_make_throws_for_unsupported_gateway(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PaymentGatewayFactory::make('unknown-gateway');
    }
}
