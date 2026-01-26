<?php

namespace App\Services\Payment\Gateways;

use App\Http\Controllers\FrontEnd\CheckoutController;
use App\Services\Membership\MembershipService;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use Stripe\Charge;

class StripeGateway implements PaymentGatewayInterface
{
    public function initialize(Request $request, array $data)
    {
        if ($data['payAmount'] < 0.50) {
            throw new \Exception('Minimum payment amount is $0.50 USD.');
        }

        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $charge = Charge::create([
                'amount' => $data['payAmount'] * 100, // Amount in cents
                'currency' => 'USD',
                'description' => 'Payment from BookApp',
                'source' => $data['stripeToken'],
            ]);

            if ($charge->status === 'succeeded' && $charge->paid === true) {
                return $data['success_url'].'?gateway=stripe';
            } else {
                return $data['cancel_url'];
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $sessionData = Session::get(PaymentService::SESSION_KEY);
        return (object) $sessionData;
    }

    public function cancel(Request $request)
    {
        return redirect()->route('frontend.payment.cancel.page')->with('error', 'Payment cancelled.');
    }
}
