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
        return view('admin.settings.payment.index',compact('totalG'));
    }

    /**
     * show online gateway page
     */
    public function online()
    {
        $data['stripe'] = PaymentGateway::where('keyword', 'stripe')->first();
        $data['paypal'] = PaymentGateway::where('keyword', 'paypal')->first();
        $data['paytm'] = PaymentGateway::where('keyword', 'paytm')->first();
        $data['instamojo'] = PaymentGateway::where('keyword', 'instamojo')->first();

        $data['stripe_info'] = json_decode($data['stripe']->information, true);
        $data['paypal_info'] = json_decode($data['paypal']->information, true);
        $data['paytm_info'] = json_decode($data['paytm']->information, true);
        $data['instamojo_info'] = json_decode($data['instamojo']->information, true);
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

        $stripe = PaymentGateway::where("keyword", "stripe")->first();

        $information = [];
        $stripe->status = $request->stripe_status;
        $information['key'] = $request->stripe_key;
        $information['secret'] = $request->stripe_secret;
        $information['text'] = "Pay via your Credit account.";
        $stripe->information = json_encode($information);
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

        $paypal = PaymentGateway::where("keyword", "paypal")->first();

        $information = [];
        $paypal->status = $request->paypal_status;
        $information['sandbox_status'] = $request->paypal_sandbox_status;
        $information['client_id'] = $request->paypal_client_id;
        $information['client_secret'] = $request->paypal_client_secret;
        $information['text'] = "Pay via your Credit account.";
        $paypal->information = json_encode($information);
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

        $paytm = PaymentGateway::where("keyword", "paytm")->first();

        $information = [];
        $paytm->status = $request->paytm_status;
        $information['environment'] = $request->paytm_environment_mode;
        $information['merchant_key'] = $request->paytm_merchant_key;
        $information['merchant_mid'] = $request->paytm_merchant_MID;
        $information['merchant_website'] = $request->paytm_merchant_website;
        $information['industry_type'] = $request->paytm_industry_type;
        $information['text'] = "Pay via your Credit account.";
        $paytm->information = json_encode($information);
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
            'insta_auth_token' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        $instamojo = PaymentGateway::where("keyword", "instamojo")->first();

        $information = [];
        $instamojo->status = $request->insta_status;
        $information['sandbox_status'] = $request->insta_sandbox_status;
        $information['key'] = $request->insta_api_key;
        $information['token'] = $request->insta_auth_token;
        $information['text'] = "Pay via your Credit account.";
        $instamojo->information = json_encode($information);
        $instamojo->save();

        Session::flash('success', 'Instamojo update successfully');
        return back();
    }
    /**
     * show online gateway page
     */
    public function offline()
    {
        return view('admin.settings.payment.offline.index');
    }
}
