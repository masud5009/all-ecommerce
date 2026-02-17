<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Services\Shipping\StedfastService;
use Illuminate\Http\Request;

class StedfastController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()
            ->select([
                'id',
                'order_number',
                'billing_name',
                'billing_phone',
                'billing_city',
                'pay_amount',
                'payment_status',
                'order_status',
                'stedfast_consignment_id',
                'stedfast_tracking_code',
                'stedfast_status',
                'stedfast_message',
                'created_at',
                'updated_at',
            ])
            ->orderBy('id', 'desc');

        if ($request->boolean('only_stedfast')) {
            $query->where(function ($q) {
                $q->whereNotNull('stedfast_consignment_id')
                    ->orWhereNotNull('stedfast_tracking_code')
                    ->orWhereNotNull('stedfast_status');
            });
        }

        if ($request->filled('status')) {
            $query->where('stedfast_status', $request->input('status'));
        }

        if ($request->filled('q')) {
            $term = trim((string)$request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('order_number', 'like', '%' . $term . '%')
                    ->orWhere('billing_name', 'like', '%' . $term . '%')
                    ->orWhere('billing_phone', 'like', '%' . $term . '%')
                    ->orWhere('stedfast_consignment_id', 'like', '%' . $term . '%')
                    ->orWhere('stedfast_tracking_code', 'like', '%' . $term . '%');
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.stedfast.index', [
            'orders' => $orders,
        ]);
    }

    public function show(int $id)
    {
        $order = Order::with(['orderItems.product', 'orderItems.variant'])->findOrFail($id);

        return view('admin.stedfast.show', [
            'order' => $order,
        ]);
    }

    public function refresh(int $id)
    {
        $order = Order::findOrFail($id);
        $result = StedfastService::refreshStatus($order);

        if (($result['status'] ?? null) === 'success') {
            return back()->with('success', __('Stedfast tracking updated.'));
        }

        $message = $result['message'] ?? __('Unable to update Stedfast tracking.');
        return back()->with('warning', $message);
    }

    public function create(int $id)
    {
        $order = Order::with('orderItems')->findOrFail($id);
        $hasPhysical = $this->orderHasPhysicalItems($order);

        $result = StedfastService::createConsignment($order, $hasPhysical);

        if (($result['status'] ?? null) === 'success') {
            return back()->with('success', __('Stedfast consignment created successfully.'));
        }

        $message = $result['message'] ?? __('Unable to create Stedfast consignment.');
        return back()->with('warning', $message);
    }

    public function balance()
    {
        $result = StedfastService::getBalance();

        if (($result['status'] ?? null) === 'success') {
            $balanceText = $this->extractBalanceText($result['data'] ?? []);
            session()->flash('stedfast_balance', $balanceText);
            return back()->with('success', __('Stedfast balance fetched successfully.'));
        }

        $message = $result['message'] ?? __('Unable to fetch Stedfast balance.');
        return back()->with('warning', $message);
    }

    private function orderHasPhysicalItems(Order $order): bool
    {
        $productIds = $order->orderItems
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        if ($productIds->isEmpty()) {
            return false;
        }

        return Product::whereIn('id', $productIds)
            ->get(['type'])
            ->contains(function ($product) {
                return strtolower((string)$product->type) === 'physical';
            });
    }

    private function extractBalanceText($data): string
    {
        if (is_array($data)) {
            $possible = [
                $data['current_balance'] ?? null,
                $data['balance'] ?? null,
                $data['available_balance'] ?? null,
                $data['available'] ?? null,
                $data['amount'] ?? null,
                $data['data']['current_balance'] ?? null,
                $data['data']['balance'] ?? null,
                $data['data']['available_balance'] ?? null,
                $data['data']['available'] ?? null,
                $data['data']['amount'] ?? null,
            ];
            foreach ($possible as $value) {
                if ($value !== null && $value !== '') {
                    return (string)$value;
                }
            }
            return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        if (is_string($data) && $data !== '') {
            return $data;
        }

        return __('N/A');
    }
}
