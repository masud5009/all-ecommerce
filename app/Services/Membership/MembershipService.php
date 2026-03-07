<?php

namespace App\Services\Membership;

use App\Jobs\SendVerificationEmail;
use App\Models\Membership;
use App\Models\User;
use App\Models\User\UserLanguage;
use App\Models\User\UserSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MembershipService
{
    public function createMembership($data)
    {
        $transactionId = $data->transaction_id ?? null;

        if ($transactionId && Membership::where('transaction_id', $transactionId)->exists()) {
            return;
        }

        $token = md5(time() . $data->username . $data->email);
        $verification_link = url('user/register/mode/verify/' . $token);

        $bs = app('websiteSettings');
        $adminLanguages = app('languages');
        //create user
        $user = User::create([
            'status' => $data->added_by == 'admin' ? 1 : 0,
            'email_verified_at' => $data->added_by == 'admin' ? Carbon::now() : NULL,
            'name' => $data->name,
            'username' => $data->username,
            'email' => $data->email,
            'password' => Hash::make($data->password),
            'company_name' => @$data->company_name,
            'country' => @$data->country,
            'remember_token' => $data->added_by == 'self' ? $token : NULL
        ]);

        //create membership
        if (isset($data->package) && !is_null($data->package)) {
            $package = $data->package;

            /**find out days based on package term */
            if ($package->term == 'monthly' && $package->is_trial == 0) {
                $days = 30;
            } elseif ($package->term == 'yearly' && $package->is_trial == 0) {
                $days = 365;
            } elseif ($package->is_trial == 1) {
                $days = (int)$package->trial_days;
            } else {
                $days = 99999999;
            }

            $expire_date = Carbon::now()->addDays($days);
            Membership::create([
                'price' => $package->price,
                'currency_text' => $bs->currency_text ?? "USD",
                'currency_text_position' => $bs->currency_text_position ?? "left",
                'currency_symbol' => $bs->currency_symbol ?? '$',
                'currency_symbol_position' => $bs->currency_symbol_position ?? "left",
                'payment_method' => $data->payment_method,
                'transaction_id' => $transactionId ?: 0,
                'status' => 1,
                'transaction_details' => $data->transaction_details ?? null,
                'settings' => json_encode($bs),
                'package_id' => $package->id,
                'user_id' => $user->id,
                'start_date' => Carbon::now(),
                'expire_date' => $expire_date
            ]);
        }

        //create user_settings
        UserSetting::create([
            'user_id' => $user->id,
            'website_title' => $bs->website_title ?? '',
            'currency_text' => $bs->currency_text ?? "USD",
            'currency_text_position' => $bs->currency_text_position ?? "left",
            'currency_symbol' => $bs->currency_symbol ?? '$',
            'currency_symbol_position' => $bs->currency_symbol_position ?? "left",
            'currency_rate' => $bs->currency_rate ?? 1,
            'timezone' => $bs->timezone ?? '',
            'website_color' => $bs->website_color ?? '',
        ]);

        //create user language
        foreach ($adminLanguages as $lang) {
            UserLanguage::create([
                'user_id' => $user->id,
                'name' => $lang->name,
                'code' => $lang->code,
                'is_default' => $lang->is_default,
                'dashboard_default' => $lang->dashboard_default,
                'direction' => $lang->direction,
                'created_by' => 'admin',
                'keywords' => $lang->customer_keywords,
            ]);
        }

        //send verification email
        if ($data->added_by == 'self') {
            dispatch(new SendVerificationEmail($user, $verification_link));
        }
        return;
    }
}
