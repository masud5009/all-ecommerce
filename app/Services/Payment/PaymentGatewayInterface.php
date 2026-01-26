<?php

namespace App\Services\Payment;

use Illuminate\Http\Request;

interface PaymentGatewayInterface
{
    /**
     * Initialize the payment (setup session, etc.)
     */
    public function initialize(Request $request, array $data);

    /**
     * Handle success callback
     */
    public function success(Request $request);

    /**
     * Handle failure or cancel
     */
    public function cancel(Request $request);
}
