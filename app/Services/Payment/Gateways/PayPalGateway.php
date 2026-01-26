<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use App\Services\PayPalClient;
use Illuminate\Support\Facades\Session;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPalGateway implements PaymentGatewayInterface
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function initialize(Request $request, array $data)
    {
        if ($data['payAmount'] <= 0) {
            throw new \Exception('Minimum payment amount must be greater than 0.');
        }

        $client = PayPalClient::client();
        $orderRequest = new OrdersCreateRequest();
        $orderRequest->prefer('return=representation');
        $orderRequest->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => round($data['payAmount'], 2)
                ]
            ]],
            'application_context' => [
                'cancel_url' => $data['cancel_url'] . '?gateway=paypal',
                'return_url' => $data['success_url'] . '?gateway=paypal'
            ]
        ];

        try {
            $response = $client->execute($orderRequest);
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    return $link->href;
                }
            }
            throw new \Exception('Something went wrong');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $orderId = $request->query('token');
        $sessionData = Session::get(PaymentService::SESSION_KEY);

        if (!$orderId || !$sessionData) {
            abort(400, 'Payment failed - session data missing');
        }

        $client = PayPalClient::client();
        $captureRequest = new OrdersCaptureRequest($orderId);
        $captureRequest->prefer('return=representation');

        try {
            $response = $client->execute($captureRequest);

            if ($response->result->status === 'COMPLETED') {
                return (object) $sessionData;
            }

            abort(400, 'Payment not completed');
        } catch (\Exception $e) {
            abort(400, 'Payment capture failed: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        // Session cleanup handled by PaymentService
        // No need to do anything here
    }
}
