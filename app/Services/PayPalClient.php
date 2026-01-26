<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalClient
{
    public static function client()
    {
        $paypal = DB::table('payment_gateways')
            ->where('keyword', 'paypal')
            ->first();

        $info = json_decode($paypal->information);

        $mode = $info->sandbox_status;
        $clientId = $info->client_id;
        $clientSecret = $info->client_secret;

        $environment = $mode === 0
            ? new ProductionEnvironment($clientId, $clientSecret)
            : new SandboxEnvironment($clientId, $clientSecret);

        return new PayPalHttpClient($environment);
    }
}
