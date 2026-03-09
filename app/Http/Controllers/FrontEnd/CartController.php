<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    protected $currentLang;

    public function __construct()
    {
        if (session()->has('lang')) {
            $this->currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $this->currentLang = Language::where('is_default', 1)->first();
        }
    }

    /**
     * Get session identifier for cart
     */
    private function getSessionId()
    {
        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', uniqid('cart_', true));
        }
        return session()->get('cart_session_id');
    }

    /**
     * Get cart items
     */
    public function index()
    {
        $sessionId = $this->getSessionId();
        $languageId = $this->currentLang->id;

        $cartItems = Cart::with(['product.content' => function ($q) use ($languageId) {
            $q->where('language_id', $languageId);
        }])
            ->where('session_id', $sessionId)
            ->get();

        $items = [];
        $totalQty = 0;
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $content = $product?->content->first();

            $image = $product?->thumbnail
                ? asset('assets/img/product/' . $product->thumbnail)
                : asset('assets/img/product/placeholder.png');

            $items[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'variant_id' => $item->variant_id,
                'name' => $content?->name ?? 'Product',
                'variant_label' => $item->variant_label,
                'image' => $image,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->price * $item->quantity,
            ];

            $totalQty += $item->quantity;
            $totalPrice += ($item->price * $item->quantity);
        }

        // Render HTML for cart items
        $html = view('front.partials.cart-items', ['items' => $items])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'totalQty' => $totalQty,
            'totalPrice' => $totalPrice,
        ]);
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|integer',
            'variant_label' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $sessionId = $this->getSessionId();

        // Check if item already exists in cart
        $existingItem = Cart::where('session_id', $sessionId)
            ->where('product_id', $request->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            // Update quantity
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            // Create new cart item
            Cart::create([
                'session_id' => $sessionId,
                'product_id' => $request->product_id,
                'variant_id' => $request->variant_id,
                'variant_label' => $request->variant_label,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);
        }

        // Get updated cart count
        $totalQty = Cart::where('session_id', $sessionId)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'totalQty' => $totalQty,
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $sessionId = $this->getSessionId();

        $cartItem = Cart::where('session_id', $sessionId)
            ->where('id', $request->cart_id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $totalQty = Cart::where('session_id', $sessionId)->sum('quantity');
        $totalPrice = Cart::where('session_id', $sessionId)
            ->selectRaw('SUM(price * quantity) as total')
            ->value('total');

        return response()->json([
            'success' => true,
            'message' => 'Cart updated',
            'totalQty' => $totalQty,
            'totalPrice' => (float) $totalPrice,
            'itemSubtotal' => $cartItem->price * $cartItem->quantity,
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|exists:carts,id',
        ]);

        $sessionId = $this->getSessionId();

        $deleted = Cart::where('session_id', $sessionId)
            ->where('id', $request->cart_id)
            ->delete();

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found',
            ], 404);
        }

        $totalQty = Cart::where('session_id', $sessionId)->sum('quantity');
        $totalPrice = Cart::where('session_id', $sessionId)
            ->selectRaw('SUM(price * quantity) as total')
            ->value('total');

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart',
            'totalQty' => $totalQty,
            'totalPrice' => (float) ($totalPrice ?? 0),
        ]);
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        $sessionId = $this->getSessionId();

        Cart::where('session_id', $sessionId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared',
            'totalQty' => 0,
            'totalPrice' => 0,
        ]);
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        $sessionId = $this->getSessionId();
        $languageId = $this->currentLang->id;

        $cartItems = Cart::with(['product.content' => function ($q) use ($languageId) {
            $q->where('language_id', $languageId);
        }])
            ->where('session_id', $sessionId)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('frontend.shop')->with('error', 'Your cart is empty');
        }

        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $content = $product?->content->first();

            $image = $product?->thumbnail
                ? asset('assets/img/product/' . $product->thumbnail)
                : asset('assets/img/product/placeholder.png');

            $itemSubtotal = $item->price * $item->quantity;

            $items[] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'name' => $content?->name ?? 'Product',
                'variant_label' => $item->variant_label,
                'image' => $image,
                'price' => (float) $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $itemSubtotal,
            ];

            $subtotal += $itemSubtotal;
        }

        $data['cartItems'] = $items;
        $data['subtotal'] = $subtotal;
        $data['shipping'] = 50; // Fixed shipping for now
        $data['total'] = $subtotal + $data['shipping'];

        return view('front.checkout', $data);
    }

    /**
     * Process checkout order
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'zip' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,online',
        ]);

        $sessionId = $this->getSessionId();
        $cartItems = Cart::where('session_id', $sessionId)->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty',
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

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully!',
            'order_number' => $order->order_number,
            'redirect' => route('cart.order.success', ['order' => $order->order_number]),
        ]);
    }

    /**
     * Order success page
     */
    public function orderSuccess($order)
    {
        $orderData = \App\Models\Order::where('order_number', $order)->first();

        if (!$orderData) {
            return redirect()->route('frontend.shop');
        }

        return view('front.order-success', ['order' => $orderData]);
    }
}
