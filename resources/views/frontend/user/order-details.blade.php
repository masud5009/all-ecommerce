@extends('frontend.user.layout')

@section('title', 'Order Details | FreshCart')
@section('page', 'user-order-details')

@section('dashboard-content')
<div class="bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Order Details</h1>
                <p class="mt-2 text-slate-600">Order #{{ $order->order_number }}</p>
            </div>
            <a href="{{ route('user.orders') }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 font-medium text-slate-900 transition hover:bg-slate-50">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Orders
            </a>
        </div>

        <!-- Order Header Card -->
        <div class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Order Date</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ optional($order->created_at)->format('d M Y') }}</p>
                    <p class="text-xs text-slate-600">{{ optional($order->created_at)->format('h:i A') }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Order Status</p>
                    <p class="mt-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ strtolower((string) $order->order_status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : (strtolower((string) $order->order_status) === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst((string) ($order->order_status ?? 'pending')) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Payment Status</p>
                    <p class="mt-2">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ strtolower((string) $order->payment_status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst((string) ($order->payment_status ?? 'pending')) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Total Amount</p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format((float) ($order->pay_amount ?? 0), 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="mb-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-6 text-lg font-semibold text-slate-900">Order Items</h2>

            @if ($order->orderItems->isEmpty())
                <div class="rounded-lg border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center text-sm text-slate-600">
                    No items in this order
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                                <th class="py-3 pr-4">Product</th>
                                <th class="py-3 pr-4">Unit Price</th>
                                <th class="py-3 pr-4">Quantity</th>
                                <th class="py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                <tr class="border-b border-slate-100 last:border-none">
                                    <td class="py-4 pr-4">
                                        <div class="flex gap-3">
                                            @if ($item->product_image)
                                                <img src="{{ asset('assets/img/product/' . $item->product_image) }}" alt="{{ $item->product_name }}" class="h-12 w-12 rounded object-cover">
                                            @else
                                                <div class="h-12 w-12 rounded bg-slate-200"></div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $item->product_name ?? 'Product' }}</p>
                                                @if ($item->product_option)
                                                    <p class="text-xs text-slate-600">{{ $item->product_option }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 pr-4 text-slate-700">{{ number_format((float) ($item->unit_price ?? 0), 2) }}</td>
                                    <td class="py-4 pr-4 text-slate-700">{{ $item->quantity }}</td>
                                    <td class="py-4 text-right font-semibold text-slate-900">{{ number_format((float) ($item->unit_price ?? 0) * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="mb-8 grid gap-6 lg:grid-cols-3">
            <!-- Left Side - Addresses -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Billing Address -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Billing Address</h3>
                    <div class="space-y-2 text-sm text-slate-700">
                        <p class="font-medium">{{ $order->billing_name ?? $user->username }}</p>
                        <p>{{ $order->billing_address ?? 'Address not provided' }}</p>
                        @if ($order->billing_city)
                            <p>{{ $order->billing_city }}, {{ $order->billing_zip ?? '' }}</p>
                        @endif
                        @if ($order->billing_phone)
                            <p>Phone: {{ $order->billing_phone }}</p>
                        @endif
                        @if ($order->billing_email)
                            <p>Email: {{ $order->billing_email }}</p>
                        @endif
                    </div>
                </div>

                <!-- Shipping Address -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Shipping Address</h3>
                    <div class="space-y-2 text-sm text-slate-700">
                        <p class="font-medium">{{ $order->shipping_name ?? $order->billing_name ?? $user->username }}</p>
                        <p>{{ $order->shipping_address ?? $order->billing_address ?? 'Address not provided' }}</p>
                        @if ($order->shipping_city)
                            <p>{{ $order->shipping_city }}, {{ $order->shipping_zip ?? '' }}</p>
                        @endif
                        @if ($order->shipping_phone)
                            <p>Phone: {{ $order->shipping_phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Side - Summary & Payment -->
            <div class="space-y-6">
                <!-- Cost Summary -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Order Summary</h3>
                    <div class="space-y-3 border-b border-slate-100 pb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Subtotal</span>
                            <span class="font-medium text-slate-900">{{ number_format((float) ($order->cart_total ?? 0), 2) }}</span>
                        </div>
                        @if ($order->discount_amount && $order->discount_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Discount</span>
                                <span class="font-medium text-emerald-600">-{{ number_format((float) $order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        @if ($order->shipping_charge && $order->shipping_charge > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Shipping</span>
                                <span class="font-medium text-slate-900">{{ number_format((float) $order->shipping_charge, 2) }}</span>
                            </div>
                        @endif
                        @if ($order->tax_amount && $order->tax_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Tax</span>
                                <span class="font-medium text-slate-900">{{ number_format((float) $order->tax_amount, 2) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex justify-between pt-4 text-lg font-bold">
                        <span class="text-slate-900">Total</span>
                        <span class="text-green-600">{{ number_format((float) ($order->pay_amount ?? 0), 2) }}</span>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold text-slate-900">Payment Method</h3>
                    <div class="text-sm text-slate-700">
                        <p class="capitalize">{{ ucfirst($order->payment_method ?? 'Not specified') }}</p>
                        <p class="mt-2 text-xs text-slate-500">
                            @if (strtolower((string) $order->payment_status) === 'completed')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-1 text-emerald-700">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"></path>
                                    </svg>
                                    Payment Completed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2 py-1 text-amber-700">
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                                    </svg>
                                    Pending
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <a href="{{ route('user.orders') }}" class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-center font-medium text-slate-900 transition hover:bg-slate-50">
                Back to Order List
            </a>
            @if (strtolower((string) $order->order_status) === 'completed')
                <a href="{{ route('frontend.shop') }}" class="flex-1 rounded-lg bg-green-600 px-4 py-2.5 text-center font-medium text-white transition hover:bg-green-700">
                    Continue Shopping
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
