<?php

namespace App\Services\Shipping;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StedfastService
{
    public static function createConsignment(Order $order, bool $hasPhysical = true): array
    {
        $settings = DB::table('settings')
            ->select('stedfast_status', 'stedfast_api_key', 'stedfast_secret_key')
            ->first();

        if (!$settings || (int)$settings->stedfast_status !== 1) {
            self::persistStatus($order, 'skipped', 'disabled');
            Log::info('Stedfast skipped', [
                'order_id' => $order->id,
                'reason' => 'disabled',
            ]);
            return ['status' => 'skipped', 'message' => 'disabled'];
        }

        if (!$hasPhysical) {
            self::persistStatus($order, 'skipped', 'no-physical-items');
            Log::info('Stedfast skipped', [
                'order_id' => $order->id,
                'reason' => 'no-physical-items',
            ]);
            return ['status' => 'skipped', 'message' => 'no-physical-items'];
        }

        if (strtolower((string)$order->order_status) === 'rejected') {
            self::persistStatus($order, 'skipped', 'order-rejected');
            Log::info('Stedfast skipped', [
                'order_id' => $order->id,
                'reason' => 'order-rejected',
            ]);
            return ['status' => 'skipped', 'message' => 'order-rejected'];
        }

        if (!empty($order->stedfast_consignment_id) || !empty($order->stedfast_tracking_code)) {
            self::persistStatus($order, 'skipped', 'already-created');
            Log::info('Stedfast skipped', [
                'order_id' => $order->id,
                'reason' => 'already-created',
            ]);
            return ['status' => 'skipped', 'message' => 'already-created'];
        }

        if (empty($settings->stedfast_api_key) || empty($settings->stedfast_secret_key)) {
            self::persistStatus($order, 'error', 'missing-credentials');
            Log::warning('Stedfast create order skipped: missing credentials', [
                'order_id' => $order->id,
            ]);
            return ['status' => 'error', 'message' => 'missing-credentials'];
        }

        $payload = self::buildPayload($order);
        $baseUrl = rtrim(config('services.stedfast.base_url', 'https://portal.packzy.com/api/v1'), '/');
        $url = $baseUrl . '/create_order';

        try {
            $response = Http::timeout(20)
                ->acceptJson()
                ->asJson()
                ->withHeaders([
                    'Api-Key' => $settings->stedfast_api_key,
                    'Secret-Key' => $settings->stedfast_secret_key,
                    'Api-Secret-Key' => $settings->stedfast_secret_key,
                ])
                ->post($url, $payload);
        } catch (\Throwable $e) {
            self::persistAttempt($order, $payload, null, 'error', $e->getMessage(), null, null);
            Log::warning('Stedfast create order request failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        $data = $response->json();
        if (!is_array($data)) {
            $data = ['raw' => $response->body()];
        }

        $consignment = is_array($data['consignment'] ?? null) ? $data['consignment'] : null;
        $consignmentId = $consignment['consignment_id'] ?? $data['consignment_id'] ?? null;
        $trackingCode = $consignment['tracking_code'] ?? $data['tracking_code'] ?? null;
        $message = $data['message'] ?? null;

        $success = $response->successful() && (!empty($consignmentId) || !empty($trackingCode));

        self::persistAttempt(
            $order,
            $payload,
            $data,
            $success ? 'success' : 'error',
            is_string($message) ? $message : null,
            $consignmentId ? (string)$consignmentId : null,
            $trackingCode ? (string)$trackingCode : null
        );

        if (!$success) {
            Log::warning('Stedfast create order failed', [
                'order_id' => $order->id,
                'status' => $response->status(),
                'response' => $data,
            ]);
            return ['status' => 'error', 'message' => is_string($message) ? $message : 'Request failed'];
        }

        Log::info('Stedfast create order success', [
            'order_id' => $order->id,
            'consignment_id' => $consignmentId,
            'tracking_code' => $trackingCode,
        ]);

        return [
            'status' => 'success',
            'message' => is_string($message) ? $message : null,
            'consignment_id' => $consignmentId,
            'tracking_code' => $trackingCode,
        ];
    }

    public static function refreshStatus(Order $order): array
    {
        $settings = self::getSettings();

        if (!$settings || (int)$settings->stedfast_status !== 1) {
            self::persistStatus($order, 'skipped', 'disabled');
            return ['status' => 'skipped', 'message' => 'disabled'];
        }

        if (empty($settings->stedfast_api_key)) {
            self::persistStatus($order, 'error', 'missing-credentials');
            return ['status' => 'error', 'message' => 'missing-credentials'];
        }

        $customerRef = $order->order_number ?: ('ORDER-' . $order->id);
        $payload = ['CustomerRef' => $customerRef];
        $url = self::resolveStatusUrl();

        try {
            $response = Http::timeout(20)
                ->acceptJson()
                ->asJson()
                ->withHeaders(self::buildStatusHeaders($settings))
                ->post($url, $payload);
        } catch (\Throwable $e) {
            self::persistAttempt($order, $payload, null, 'error', $e->getMessage(), $order->stedfast_consignment_id, $order->stedfast_tracking_code);
            Log::warning('Stedfast tracking request failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        $data = $response->json();
        if (!is_array($data)) {
            $data = ['raw' => $response->body()];
        }

        $orderFoundRaw = $data['OrderFound'] ?? null;
        $orderFound = null;
        if (is_bool($orderFoundRaw)) {
            $orderFound = $orderFoundRaw;
        } elseif (is_string($orderFoundRaw)) {
            $value = strtolower(trim($orderFoundRaw));
            if ($value === 'true' || $value === '1' || $value === 'yes') {
                $orderFound = true;
            } elseif ($value === 'false' || $value === '0' || $value === 'no') {
                $orderFound = false;
            }
        } elseif (is_int($orderFoundRaw)) {
            $orderFound = (bool)$orderFoundRaw;
        }

        $tripStatus = $data['TripStatus'] ?? null;
        $message = $data['ErrorMessage'] ?? $data['Message'] ?? null;
        $consRef = $data['ConsRef'] ?? null;
        $status = $orderFound === false ? 'not-found' : ($tripStatus ?: 'success');

        self::persistAttempt(
            $order,
            $payload,
            $data,
            is_string($status) ? $status : 'success',
            is_string($message) ? $message : ($orderFound === false ? 'order-not-found' : null),
            $consRef ? (string)$consRef : $order->stedfast_consignment_id,
            $order->stedfast_tracking_code
        );

        if (!$response->successful()) {
            Log::warning('Stedfast tracking failed', [
                'order_id' => $order->id,
                'status' => $response->status(),
                'response' => $data,
            ]);
            return ['status' => 'error', 'message' => is_string($message) ? $message : 'Request failed'];
        }

        Log::info('Stedfast tracking success', [
            'order_id' => $order->id,
            'customer_ref' => $customerRef,
            'trip_status' => $tripStatus,
        ]);

        return [
            'status' => 'success',
            'message' => is_string($message) ? $message : null,
            'consignment_id' => $consRef,
            'delivery_status' => $tripStatus,
            'order_found' => $orderFound,
        ];
    }

    public static function getBalance(): array
    {
        $settings = self::getSettings();

        if (!$settings || (int)$settings->stedfast_status !== 1) {
            return ['status' => 'skipped', 'message' => 'disabled'];
        }

        if (empty($settings->stedfast_api_key) || empty($settings->stedfast_secret_key)) {
            return ['status' => 'error', 'message' => 'missing-credentials'];
        }

        $baseUrl = self::resolveBaseUrl();
        $url = $baseUrl . '/get_balance';

        try {
            $response = Http::timeout(20)
                ->acceptJson()
                ->asJson()
                ->withHeaders(self::buildHeaders($settings))
                ->get($url);
            if ($response->status() === 405) {
                $response = Http::timeout(20)
                    ->acceptJson()
                    ->asJson()
                    ->withHeaders(self::buildHeaders($settings))
                    ->post($url);
            }
        } catch (\Throwable $e) {
            Log::warning('Stedfast balance request failed', [
                'error' => $e->getMessage(),
            ]);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }

        $data = $response->json();
        if (!is_array($data)) {
            $data = ['raw' => $response->body()];
        }

        if (!$response->successful()) {
            Log::warning('Stedfast balance fetch failed', [
                'status' => $response->status(),
                'response' => $data,
            ]);
            return ['status' => 'error', 'message' => $data['message'] ?? 'Request failed', 'data' => $data];
        }

        Log::info('Stedfast balance fetch success', [
            'response' => $data,
        ]);

        return ['status' => 'success', 'data' => $data];
    }

    private static function buildPayload(Order $order): array
    {
        $recipientAddress = $order->shipping_address ?: $order->billing_address;
        $codAmount = 0;
        if (strtolower((string)$order->payment_status) !== 'completed') {
            $codAmount = (float)($order->pay_amount ?? 0);
        }

        $payload = [
            'invoice' => $order->order_number ?: ('ORDER-' . $order->id),
            'recipient_name' => $order->billing_name ?: 'Customer',
            'recipient_phone' => $order->billing_phone ?: '',
            'recipient_address' => $recipientAddress ?: '',
            'cod_amount' => $codAmount,
            'note' => 'Order #' . ($order->order_number ?: $order->id),
        ];

        if (!empty($order->billing_city)) {
            $payload['recipient_city'] = $order->billing_city;
        }

        return $payload;
    }

    private static function persistAttempt(
        Order $order,
        array $payload,
        $response,
        string $status,
        ?string $message,
        ?string $consignmentId,
        ?string $trackingCode
    ): void {
        Order::where('id', $order->id)->update([
            'stedfast_status' => $status,
            'stedfast_message' => $message,
            'stedfast_consignment_id' => $consignmentId,
            'stedfast_tracking_code' => $trackingCode,
            'stedfast_payload' => self::encodeJson($payload),
            'stedfast_response' => self::encodeJson($response),
        ]);
    }

    private static function persistStatus(Order $order, string $status, ?string $message): void
    {
        Order::where('id', $order->id)->update([
            'stedfast_status' => $status,
            'stedfast_message' => $message,
        ]);
    }

    private static function getSettings(): ?object
    {
        return DB::table('settings')
            ->select('stedfast_status', 'stedfast_api_key', 'stedfast_secret_key')
            ->first();
    }

    private static function resolveBaseUrl(): string
    {
        return rtrim(config('services.stedfast.base_url', 'https://portal.packzy.com/api/v1'), '/');
    }

    private static function buildHeaders(object $settings): array
    {
        return [
            'Api-Key' => $settings->stedfast_api_key,
            'Secret-Key' => $settings->stedfast_secret_key,
            'Api-Secret-Key' => $settings->stedfast_secret_key,
        ];
    }

    private static function resolveStatusUrl(): string
    {
        return rtrim(config('services.stedfast.status_url', 'https://middleware.podapp.com.au/passthrough/customerorderstatus'), '/');
    }

    private static function buildStatusHeaders(object $settings): array
    {
        return [
            'X-Steadfast-Api-Key' => $settings->stedfast_api_key,
        ];
    }

    private static function encodeJson($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $json = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $json === false ? null : $json;
    }
}
