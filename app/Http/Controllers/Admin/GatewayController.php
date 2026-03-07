<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class GatewayController extends Controller
{
    /**
     * payemnt gateway page
     */
    public function index()
    {
        $totalG = PaymentGateway::count();

        return view('admin.settings.payment.index', compact('totalG'));
    }

    /**
     * show online gateway page
     */
    public function online()
    {
        $gateways = [
            'stripe' => 'Stripe',
            'paypal' => 'Paypal',
            'paytm' => 'Paytm',
            'instamojo' => 'Instamojo',
            'sslcommerz' => 'SSLCommerz',
        ];

        $data = [];

        foreach ($gateways as $keyword => $name) {
            $data[$keyword] = $this->gateway($keyword, $name);
            $data[$keyword . '_info'] = json_decode($data[$keyword]->information, true) ?: [];
        }

        return view('admin.settings.payment.online', $data);
    }

    /**
     * upate stripe
     */
    public function stripe(Request $request)
    {
        $rules = [
            'stripe_status' => 'required',
            'stripe_key' => 'required',
            'stripe_secret' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $stripe = $this->gateway('stripe', 'Stripe');

        $stripe->status = $request->stripe_status;
        $stripe->information = json_encode([
            'key' => $request->stripe_key,
            'secret' => $request->stripe_secret,
            'text' => 'Pay via your Credit account.',
        ]);
        $stripe->save();

        Session::flash('success', 'Stripe update successfully');

        return back();
    }

    /**
     * upate paypal
     */
    public function paypal(Request $request)
    {
        $rules = [
            'paypal_status' => 'required',
            'paypal_sandbox_status' => 'required',
            'paypal_client_id' => 'required',
            'paypal_client_secret' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $paypal = $this->gateway('paypal', 'Paypal');

        $paypal->status = $request->paypal_status;
        $paypal->information = json_encode([
            'sandbox_status' => $request->paypal_sandbox_status,
            'client_id' => $request->paypal_client_id,
            'client_secret' => $request->paypal_client_secret,
            'text' => 'Pay via your Credit account.',
        ]);
        $paypal->save();

        Session::flash('success', 'Paypal update successfully');

        return back();
    }

    /**
     * upate paytm
     */
    public function paytm(Request $request)
    {
        $rules = [
            'paytm_status' => 'required',
            'paytm_environment_mode' => 'required',
            'paytm_merchant_MID' => 'required',
            'paytm_merchant_key' => 'required',
            'paytm_merchant_website' => 'required',
            'paytm_industry_type' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $paytm = $this->gateway('paytm', 'Paytm');

        $paytm->status = $request->paytm_status;
        $paytm->information = json_encode([
            'environment' => $request->paytm_environment_mode,
            'merchant_key' => $request->paytm_merchant_key,
            'merchant_mid' => $request->paytm_merchant_MID,
            'merchant_website' => $request->paytm_merchant_website,
            'industry_type' => $request->paytm_industry_type,
            'text' => 'Pay via your Credit account.',
        ]);
        $paytm->save();

        Session::flash('success', 'Paytm update successfully');

        return back();
    }

    /**
     * upate instamojo
     */
    public function instamojo(Request $request)
    {
        $rules = [
            'insta_status' => 'required',
            'insta_sandbox_status' => 'required',
            'insta_api_key' => 'required',
            'insta_auth_token' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $instamojo = $this->gateway('instamojo', 'Instamojo');

        $instamojo->status = $request->insta_status;
        $instamojo->information = json_encode([
            'sandbox_status' => $request->insta_sandbox_status,
            'key' => $request->insta_api_key,
            'token' => $request->insta_auth_token,
            'text' => 'Pay via your Credit account.',
        ]);
        $instamojo->save();

        Session::flash('success', 'Instamojo update successfully');

        return back();
    }

    /**
     * update sslcommerz
     */
    public function sslcommerz(Request $request)
    {
        $rules = [
            'sslcommerz_status' => 'required|in:0,1',
            'sslcommerz_mode' => 'required|in:sandbox,live',
            'sslcommerz_store_id' => 'required|max:255',
            'sslcommerz_store_password' => 'required|max:255',
            'sslcommerz_currency' => 'required|size:3',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $sslcommerz = $this->gateway('sslcommerz', 'SSLCommerz');

        $sslcommerz->status = $request->sslcommerz_status;
        $sslcommerz->information = json_encode([
            'mode' => strtolower((string) $request->sslcommerz_mode),
            'store_id' => $request->sslcommerz_store_id,
            'store_password' => $request->sslcommerz_store_password,
            'currency' => strtoupper((string) $request->sslcommerz_currency),
            'text' => 'Pay securely via SSLCommerz.',
        ]);
        $sslcommerz->save();

        Session::flash('success', 'SSLCommerz update successfully');

        return back();
    }

    /**
     * show online gateway page
     */
    public function offline()
    {
        return view('admin.settings.payment.offline.index');
    }

    private function gateway(string $keyword, string $name): PaymentGateway
    {
        return PaymentGateway::firstOrCreate(
            ['keyword' => $keyword],
            [
                'name' => $name,
                'type' => 'automatic',
                'information' => json_encode([
                    'text' => 'Pay via your Credit account.',
                ]),
                'status' => 0,
            ]
        );
    }
}
