<?php

namespace App\Services\Payment\Gateways;

use Illuminate\Http\Request;
use App\Models\Admin\PaymentGateway;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use App\Services\Payment\PaymentService;
use App\Services\Payment\PaymentGatewayInterface;

class SslCommerzGateway implements PaymentGatewayInterface
{
    private const LIVE_BASE_URL = 'https://securepay.sslcommerz.com';
    private const SANDBOX_BASE_URL = 'https://sandbox.sslcommerz.com';

    public function initialize(Request $request, array $data)
    {
        if ((float) $data['payAmount'] <= 0) {
            throw new \Exception('Minimum payment amount must be greater than 0.');
        }

        $config = $this->getConfig();
        $payload = $this->buildPayload($data, $config);

        Session::put(PaymentService::SESSION_KEY, array_merge(
            Session::get(PaymentService::SESSION_KEY, []),
            [
                'transaction_id' => $payload['tran_id'],
                'currency' => $payload['currency'],
            ]
        ));

        Cache::put(
            $this->paymentCacheKey($payload['tran_id']),
            Session::get(PaymentService::SESSION_KEY, []),
            now()->addHours(2)
        );

        $response = Http::asForm()
            ->timeout(30)
            ->post($this->apiBaseUrl($config) . '/gwprocess/v4/api.php', $payload);

        if (! $response->successful()) {
            throw new \Exception('Unable to connect to SSLCommerz.');
        }

        $result = $response->json();

        if (($result['status'] ?? null) !== 'SUCCESS' || empty($result['GatewayPageURL'])) {
            throw new \Exception($result['failedreason'] ?? 'Unable to initialize SSLCommerz payment.');
        }

        return (object) [
            'url' => $result['GatewayPageURL'],
        ];
    }

    public function success(Request $request)
    {
        $payload = $request->all();
        $requestStatus = strtoupper((string) ($payload['status'] ?? ''));

        if (! in_array($requestStatus, ['VALID', 'VALIDATED'], true)) {
            throw new \Exception('Payment was not approved by SSLCommerz.');
        }

        $sessionData = null;

        if (! empty($payload['tran_id'])) {
            $sessionData = Cache::get($this->paymentCacheKey((string) $payload['tran_id']));
        }

        if (! $sessionData) {
            $sessionData = Session::get(PaymentService::SESSION_KEY);
        }

        if (! $sessionData) {
            throw new \Exception('Payment session data is missing.');
        }

        if (($sessionData['transaction_id'] ?? null) !== ($payload['tran_id'] ?? null)) {
            throw new \Exception('Transaction reference mismatch.');
        }

        $config = $this->getConfig();
        $hasSignature = ! empty($payload['verify_sign']) && ! empty($payload['verify_key']);
        $isLiveMode = in_array($config['mode'], ['live', 'production', '1'], true);

        if ($isLiveMode && $hasSignature && ! $this->hasValidSignature($payload, $config['store_password'])) {
            throw new \Exception('Invalid SSLCommerz callback signature.');
        }

        // Sandbox callbacks may not always include signature fields on browser redirects.
        if ($isLiveMode && ! $hasSignature) {
            throw new \Exception('Missing SSLCommerz callback signature in live mode.');
        }

        $validationData = $this->validateTransaction($payload['val_id'] ?? null, $config);

        if (! $this->isValidValidationResponse($validationData, $sessionData)) {
            throw new \Exception('SSLCommerz payment validation failed.');
        }

        $sessionData['transaction_id'] = $payload['tran_id'];
        $sessionData['transaction_details'] = json_encode([
            'callback' => $payload,
            'validation' => $validationData,
        ]);

        Cache::forget($this->paymentCacheKey((string) $payload['tran_id']));

        return (object) $sessionData;
    }

    public function cancel(Request $request)
    {
        return null;
    }

    private function buildPayload(array $data, array $config): array
    {
        $transactionId = $data['transaction_id'] ?? ('MEM' . now()->format('ymdHis'));
        $successUrl = $this->appendQuery($data['success_url'], ['gateway' => 'sslcommerz']);
        $cancelUrl = $this->appendQuery($data['cancel_url'], ['gateway' => 'sslcommerz']);
        $failUrl = $this->appendQuery($data['cancel_url'], [
            'gateway' => 'sslcommerz',
            'error' => 'Payment failed.',
        ]);

        return [
            'store_id' => $config['store_id'],
            'store_passwd' => $config['store_password'],
            'total_amount' => number_format((float) $data['payAmount'], 2, '.', ''),
            'currency' => $config['currency'],
            'tran_id' => $transactionId,
            'success_url' => $successUrl,
            'fail_url' => $failUrl,
            'cancel_url' => $cancelUrl,
            'shipping_method' => 'NO',
            'product_name' => $data['package']->title ?? 'Membership Package',
            'product_category' => 'Membership',
            'product_profile' => 'non-physical-goods',
            'cus_name' => $data['name'],
            'cus_email' => $data['email'],
            'cus_add1' => $data['address'] ?: ($data['company_name'] ?: 'N/A'),
            'cus_city' => $data['city'] ?: 'Dhaka',
            'cus_postcode' => $data['postcode'] ?: '1207',
            'cus_country' => $data['country'] ?: 'Bangladesh',
            'cus_phone' => $data['phone'] ?: '01700000000',
            'value_a' => $transactionId,
            'value_b' => $data['payment_method'] ?? 'sslcommerz',
        ];
    }

    private function validateTransaction(?string $valId, array $config): array
    {
        if (! $valId) {
            throw new \Exception('SSLCommerz validation id is missing.');
        }

        $response = Http::timeout(30)->get($this->apiBaseUrl($config) . '/validator/api/validationserverAPI.php', [
            'val_id' => $valId,
            'store_id' => $config['store_id'],
            'store_passwd' => $config['store_password'],
            'v' => 1,
            'format' => 'json',
        ]);

        if (! $response->successful()) {
            throw new \Exception('Unable to validate SSLCommerz transaction.');
        }

        return $response->json() ?? [];
    }

    private function hasValidSignature(array $payload, string $storePassword): bool
    {
        if (empty($payload['verify_sign']) || empty($payload['verify_key'])) {
            return false;
        }

        $preDefinedKey = explode(',', (string) $payload['verify_key']);
        $newData = [];

        foreach ($preDefinedKey as $value) {
            if (! array_key_exists($value, $payload)) {
                return false;
            }

            $newData[$value] = $payload[$value];
        }

        $newData['store_passwd'] = md5($storePassword);
        ksort($newData);

        $generated = md5(http_build_query($newData));
        $incoming = strtolower(trim((string) $payload['verify_sign']));

        return hash_equals(strtolower($generated), $incoming);
    }

    private function isValidValidationResponse(array $validationData, array $sessionData): bool
    {
        $status = strtoupper((string) ($validationData['status'] ?? ''));

        if (! in_array($status, ['VALID', 'VALIDATED'], true)) {
            return false;
        }

        if ((string) ($validationData['tran_id'] ?? '') !== (string) ($sessionData['transaction_id'] ?? '')) {
            return false;
        }

        $validatedCurrency = strtoupper((string) ($validationData['currency_type'] ?? ($validationData['currency'] ?? '')));
        $expectedCurrency = strtoupper((string) ($sessionData['currency'] ?? ''));

        if ($expectedCurrency !== '' && $validatedCurrency !== '' && $validatedCurrency !== $expectedCurrency) {
            return false;
        }

        $validatedAmount = (float) ($validationData['currency_amount'] ?? ($validationData['amount'] ?? 0));
        $expectedAmount = (float) ($sessionData['payAmount'] ?? 0);

        return abs($validatedAmount - $expectedAmount) < 0.01;
    }

    private function getConfig(): array
    {
        $gateway = PaymentGateway::where('keyword', 'sslcommerz')->first();
        $information = json_decode($gateway?->information ?? '{}', true) ?: [];
        $storeId = $information['store_id'] ?? null;
        $storePassword = $information['store_password'] ?? ($information['store_passwd'] ?? null);
        $rawMode = $information['mode']
            ?? $information['sslcommerz_mode']
            ?? $information['sandbox_status']
            ?? 'sandbox';

        if (! $gateway || ! $storeId || ! $storePassword) {
            throw new \Exception('SSLCommerz gateway is not configured.');
        }

        return [
            'store_id' => $storeId,
            'store_password' => $storePassword,
            'mode' => $this->normalizeMode((string) $rawMode),
            'currency' => strtoupper((string) ($information['currency'] ?? 'BDT')),
        ];
    }

    private function normalizeMode(string $mode): string
    {
        $normalized = strtolower(trim($mode));

        if (in_array($normalized, ['live', 'production', 'prod', '1'], true)) {
            return 'live';
        }

        return 'sandbox';
    }

    private function apiBaseUrl(array $config): string
    {
        return in_array($config['mode'], ['live', 'production', '1'], true)
            ? self::LIVE_BASE_URL
            : self::SANDBOX_BASE_URL;
    }

    private function appendQuery(string $url, array $query): string
    {
        return $url . (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
    }

    private function paymentCacheKey(string $transactionId): string
    {
        return 'payment_data_' . $transactionId;
    }
}
