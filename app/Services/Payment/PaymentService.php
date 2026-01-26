<?php

namespace App\Services\Payment;

use App\Services\PaymentHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PaymentService
{
    const SESSION_KEY = 'payment_data';

    /**
     * payment process
     */
    public function pay(Request $request, string $gateway, array $paymentData)
    {
        $this->setSessionData($paymentData);
        $gatewayInstance = PaymentGatewayFactory::make($gateway);

        return $gatewayInstance->initialize($request, $paymentData);
    }

    public function handleSuccess(Request $request, ?string $gateway)
    {
        if (!$gateway) {
            $this->clearSessionData();
            return;
        }

        $gatewayInstance = PaymentGatewayFactory::make($gateway);
        $metadata = $gatewayInstance->success($request);

        // Handle business logic
        PaymentHandler::handleSuccess($metadata);

        $this->clearSessionData();

        return $metadata;
    }

    public function handleCancel(Request $request, ?string $gateway)
    {
        if (!$gateway) {
            $this->clearSessionData();
            return;
        }

        $gatewayInstance = PaymentGatewayFactory::make($gateway);
        $gatewayInstance->cancel($request);
        $this->clearSessionData();
    }

    protected function setSessionData(array $data)
    {
        Session::put(self::SESSION_KEY, $data);
    }

    protected function getSessionData()
    {
        return Session::get(self::SESSION_KEY);
    }

    protected function clearSessionData()
    {
        Session::forget(self::SESSION_KEY);
    }
}
