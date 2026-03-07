<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin\Package;
use App\Models\Admin\PaymentGateway;
use App\Services\Payment\PaymentGatewayFactory;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * view pricing page
     */
    public function pricing()
    {
        $data['packages'] = Package::where('status', 1)->get();
        return view('frontend.pricing', $data);
    }

    /**
     * view registration page
     */
    public function registrationPage(Request $request, $id)
    {
        $data['package_id'] = $id;
        return view('frontend.registration', $data);
    }

    /**
     * check validation for registration a user
     */
    public function checkRegistration(Request $request)
    {
        $request->validate([
            'username' => 'required|max:20|alpha_num|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',        // at least one uppercase
                'regex:/[a-z]/',        // at least one lowercase
                'regex:/[0-9]/',        // at least one number
                'regex:/[@$!%*?&]/'     // at least one special character
            ],
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, number, and special character.'
        ]);

        $data['username'] = $request->username;
        $data['email'] = $request->email;
        $data['password'] = $request->password;
        $data['package_id'] = $request->package_id;
        Session::put('registration_data', $data);

        $url = route('frontend.checkout.view');

        return response()->json(['status' => 'success', 'url' => $url]);
    }

    /**
     * view checkout page
     */
    public function checkoutPage()
    {
        $data['registration_data'] = Session::get('registration_data');
        $data['package'] = Package::findOrFail($data['registration_data']['package_id']);
        $data['payment_methods'] = PaymentGateway::where('status', 1)
            ->whereIn('keyword', PaymentGatewayFactory::supportedGateways())
            ->get();

        return view('frontend.checkout', $data);
    }

    /**
     * checkout process
     */
    public function checkoutSubmit(Request $request)
    {
        $request->validate([
            'fullname' => 'required|max:100',
            'username' => 'required|max:20|alpha_num|unique:users,username',
            'payment_method' => [
                'required',
                'exists:payment_gateways,keyword',
                Rule::in(PaymentGatewayFactory::supportedGateways()),
            ],
            'email' => 'required|email|unique:users,email',
            'company_name' => 'nullable|max:100',
            'country' => 'nullable|max:255',
            'phone' => 'nullable|max:30',
            'address' => 'nullable|max:255',
            'city' => 'nullable|max:100',
            'postcode' => 'nullable|max:20',
            'package_id' => 'required|exists:packages,id',
        ]);

        $package = Package::findOrFail($request->package_id);
        $registration_data = session()->get('registration_data', []);
        $transactionId = 'MEM' . now()->format('ymdHis') . Str::upper(Str::random(6));
        $currency = app('websiteSettings')->currency_text ?? 'USD';

        //send data for payment and store all nessary database data
        $data = [
            'added_by' => 'self',
            'package' => $package ?? [],
            'payAmount' => $package->price ?? 0,
            'purpose' => 'membership',
            'email' => $request->email,
            'name' => $request->fullname,
            'username' => $request->username,
            'password' => $registration_data['password'],
            'transaction_id' => $transactionId,
            'payment_method' => $request->payment_method,
            'company_name' => $request->company_name,
            'country' => $request->country,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'currency' => strtoupper((string) $currency),
            'stripeToken' => $request->stripeToken ?? null, // Stripe token
            'cancel_url' => route('frontend.checkout.payment_cancel'),
            'success_url' => route('frontend.checkout.payment_success'),
        ];

        try {
            $result = $this->paymentService->pay($request, $request->payment_method, $data);

            return response()->json([
                'status' => 'success',
                'redirect_url' => $result->url ?? $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'redirect_url' => route('frontend.checkout.payment_cancel')
            ], 400);
        }
    }

    // Payment Success Callback
    public function paymentSuccess(Request $request)
    {
        $gateway = $this->resolveGateway($request);

        if (!$gateway) {
            return redirect()->route('frontend.payment_cancel.view')
                ->with('error', 'Unable to determine payment gateway.');
        }

        try {
            $this->paymentService->handleSuccess($request, $gateway);

            return redirect()->route('frontend.payment_success.view');
        } catch (\Throwable $e) {
            $this->paymentService->handleCancel($request, $gateway);

            return redirect()->route('frontend.payment_cancel.view')
                ->with('error', $e->getMessage());
        }
    }

    // Payment Cancel Callback
    public function paymentCancel(Request $request)
    {
        $gateway = $this->resolveGateway($request);
        $errorMessage = $request->query('error') ?: $request->input('error');

        if ($gateway) {
            $this->paymentService->handleCancel($request, $gateway);
        }

        return redirect()->route('frontend.payment_cancel.view')
            ->with('error', $errorMessage);
    }


    /**
     * payment success view page
     */
    public function successPage()
    {
        return view('frontend.success');
    }

    /**
     * payment cancel view page
     */
    public function cancelPage()
    {
        return view('frontend.cancel');
    }

    private function resolveGateway(Request $request): ?string
    {
        $sessionData = Session::get(PaymentService::SESSION_KEY, []);

        return $request->query('gateway')
            ?: $request->input('gateway')
            ?: ($sessionData['payment_method'] ?? null);
    }
}
