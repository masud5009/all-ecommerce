<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchEmailRule;
use App\Http\Helpers\MailConfig;
use App\Models\Admin\MailTemplate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\User\StoreRequest;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * dashboard
     */
    public function dashboard()
    {
        return redirect()->route('frontend.index');
    }
    /**
     * Show register page
     */
    public function signup(Request $request)
    {
        return view('frontend.auth.register');
    }

    /**
     * Show register submit
     */
    public function signup_submit(StoreRequest $request)
    {
        $validated = $request->validated();

        $user = new User();
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->status = 1;
        $user->password = Hash::make($validated['password']);
        $user->save();

        // get the mail template information from db
        $mailTemplate = MailTemplate::query()->where('type', '=', 'verify_email')->first();
        $mailData['subject'] = $mailTemplate->subject;
        $mailBody = $mailTemplate->body;
        $link = '<a href=' . url("user/signup-verify/" . $user->id) . '>Click Here</a>';

        $mailBody = str_replace('{customer_name}', $user->username, $mailBody);
        $mailBody = str_replace('{verification_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', app('websiteSettings')->website_title, $mailBody);

        $mailData['body'] = $mailBody;
        $mailData['recipient'] = $user->email;
        $mailData['sessionMessage'] = __('A verification mail has been sent to your email address');

        MailConfig::send($mailData);

        $successMessage = __('A verification mail has been sent to your email address');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $successMessage);
    }
    /**
     * Show login page
     */
    public function login(Request $request)
    {
        return view('frontend.auth.login');
    }

    /**
     * Submit login form
     */
    public function loginSubmit(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $loginInput = $request->input('email');
        $password = $request->input('password');

        // Determine if login input is email or username
        $field = filter_var($loginInput, \FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $loginInput)->first();

        // Login attempt with correct credentials
        if ($user && Auth::guard('web')->attempt([$field => $loginInput, 'password' => $password], $request->boolean('remember'))) {
            $authUser = Auth::guard('web')->user();

            // Check email verification
            if (is_null($authUser->email_verified_at)) {
                Auth::guard('web')->logout();

                return response()->json([
                    'success' => false,
                    'message' => __('Please, verify your email address.'),
                    'redirect_url' => null
                ], 401);
            }

            // Check account status
            if ($authUser->status == 0) {
                Auth::guard('web')->logout();

                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, your account has been deactivated.',
                    'redirect_url' => null
                ], 401);
            }

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'redirect_url' => route('user.dashboard')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => __('Oops, Incorrect credentials!'),
                'redirect_url' => null
            ], 401);
        }
    }

    /**
     * Forget password page
     */
    public function forgetPassword()
    {
        return view('frontend.auth.forget_password');
    }

    /**
     * Store sinup user
     */
    public function resetPasswordSubmit(Request $request)
    {
        if ($request->session()->has('userEmail')) {
            // get the user email from session
            $emailAddress = $request->session()->get('userEmail');

            $rules = [
                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    'max:64',
                    'confirmed',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[^A-Za-z0-9]/',
                ],
                'new_password_confirmation' => 'required|string'
            ];

            $messages = [
                'new_password.confirmed' => 'Password confirmation failed.',
                'new_password.regex' => 'Password must include uppercase, lowercase, number and special character.',
                'new_password_confirmation.required' => 'The confirm new password field is required.'
            ];

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors(),
                    ], 422);
                }

                return redirect()->back()->withErrors($validator->errors());
            }

            $user = User::query()->where('email', '=', $emailAddress)->first();

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password updated successfully.',
                    'redirect_url' => route('user.login'),
                ]);
            }

            Session::flash('success', 'Password updated successfully.');
        } else {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong!',
                ], 400);
            }

            Session::flash('error', 'Something went wrong!');
        }

        return redirect()->route('user.login');
    }

    /**
     * Send mail for forget password
     */
    public function forgetNow(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'string',
                'max:255',
                'email:rfc,dns',
                new MatchEmailRule('user')
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }


        $user = User::query()->where('email', '=', $request->email)->first();

        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);

        // get the mail template information from db
        $mailTemplate = MailTemplate::query()->where('type', '=', 'reset_password')->first();
        $mailData['subject'] = $mailTemplate->subject;
        $mailBody = $mailTemplate->body;

        // get the website title info from db
        $info = app('websiteSettings');

        $name = $user->username;
        $link = '<a href=' . url("user/reset-password") . '>Click Here</a>';

        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

        $mailData['body'] = $mailBody;
        $mailData['recipient'] = $user->email;
        $mailData['sessionMessage'] = __('A mail has been sent to your email address');

        MailConfig::send($mailData);

        return redirect()->back();
    }

    public function resetPassword()
    {
        if (session()->has('userEmail')) {
            return view('frontend.auth.reset-password');
        } else {
            return redirect()->back();
        }
    }


    /**
     * verify link send from here
     */
    public function verifyEmail(Request $request, $token)
    {
        // Prevent brute force guessing
        if (strlen($token) < 20) {
            session()->flash('error', __('Invalid verification link'));
            return redirect()->route('frontend.index');
        }

        $user = User::where('remember_token', $token)
            ->whereNull('email_verified_at') // Already verified users can't use again
            ->first();

        if (!$user) {
            session()->flash('error', __('This verification link is invalid or has already been used'));
            return redirect()->route('frontend.index');
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->status = 1;
        $user->remember_token = null;
        $user->save();

        Auth::login($user);

        return redirect()->route('user.dashboard')
            ->with('success', 'Your email has been verified successfully! Welcome to the platform.');
    }

    /**
     * logout
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Session::forget('secret_login');

        return redirect()->route('user.login');
    }
}
