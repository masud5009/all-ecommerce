<?php

namespace App\Services\Shop;

use App\Models\Cart;
use Illuminate\Http\Request;

class OrderService
{
    public static function createOrder(Request $request, $sessionId)
    {
        $cartItems = Cart::where('session_id', $sessionId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => 'error',
                'message' =>  __('Your cart is empty'),
            ], 400);
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $shipping = 50;
        $total = $subtotal + $shipping;

        // Create order
        $order = \App\Models\Order::create([
            'order_number' => strtoupper(substr(uniqid(), -8)),
            'billing_name' => $request->name,
            'billing_email' => $request->email,
            'billing_phone' => $request->phone,
            'billing_address' => $request->address,
            'billing_city' => $request->city,
            'shipping_address' => $request->address . ', ' . $request->city . ($request->zip ? ', ' . $request->zip : ''),
            'payment_method' => $request->payment_method === 'cod' ? 'Cash Payment' : 'Online Payment',
            'cart_total' => $subtotal,
            'shipping_charge' => $shipping,
            'pay_amount' => $total,
            'order_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'product_price' => $item->price,
                'qty' => $item->quantity,
                'variations' => $item->variant_label ? json_encode([['label' => $item->variant_label]]) : null,
            ]);
        }
        // Clear cart
        Cart::where('session_id', $sessionId)->delete();

        return ['status' => 'success', 'order' => $order];
    }
}
