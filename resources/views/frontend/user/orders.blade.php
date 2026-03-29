@extends('frontend.user.layout')

@section('title', 'My Orders | FreshCart')
@section('page', 'user-orders')

@section('dashboard-content')
<div class="bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
    <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8 rounded-3xl border border-emerald-100 bg-white/90 p-6 shadow-xl shadow-emerald-100/40 sm:p-8">
            <h1 class="text-3xl font-bold text-slate-900">My Orders</h1>
            <p class="mt-2 text-slate-600">View and track all your orders below</p>
        </div>

        <!-- Filter Buttons -->
        <div class="mb-6 flex flex-wrap gap-2">
            <a href="{{ route('user.orders') }}" class="rounded-lg px-4 py-2 text-sm font-medium transition {{ !request('status') ? 'bg-green-600 text-white' : 'border border-slate-300 bg-white text-slate-900 hover:bg-slate-50' }}">
                All Orders
            </a>
            <a href="{{ route('user.orders', ['status' => 'pending']) }}" class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request('status') === 'pending' ? 'bg-amber-600 text-white' : 'border border-slate-300 bg-white text-slate-900 hover:bg-slate-50' }}">
                Pending
            </a>
            <a href="{{ route('user.orders', ['status' => 'completed']) }}" class="rounded-lg px-4 py-2 text-sm font-medium transition {{ request('status') === 'completed' ? 'bg-emerald-600 text-white' : 'border border-slate-300 bg-white text-slate-900 hover:bg-slate-50' }}">
                Completed
            </a>
        </div>

        @if ($orders->isEmpty())
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
                <svg class="mx-auto h-16 w-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m0 0L4 7m8 4v10l8-4v-10M4 7v10l8 4m0 0v-10"></path>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-900">No orders yet</h3>
                <p class="mt-1 text-sm text-slate-600">You haven't placed any orders yet. Start shopping to see your orders here.</p>
                <a href="{{ route('frontend.shop') }}" class="mt-6 inline-block rounded-lg bg-green-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-green-700">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($orders as $order)
                    <div class="rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                        <!-- Order Header -->
                        <div class="border-b border-slate-100 px-6 py-4 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Order #{{ $order->order_number }}</h3>
                                <p class="text-sm text-slate-500">{{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2 sm:mt-0">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ strtolower((string) $order->order_status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : (strtolower((string) $order->order_status) === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700') }}">
                                    Order {{ ucfirst((string) ($order->order_status ?? 'pending')) }}
                                </span>
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ strtolower((string) $order->payment_status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                    Payment {{ ucfirst((string) ($order->payment_status ?? 'pending')) }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="px-6 py-4">
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <div>
                                    <p class="text-xs font-semibold uppercase text-slate-500">Total Amount</p>
                                    <p class="mt-1 text-xl font-bold text-slate-900">{{ number_format((float) ($order->pay_amount ?? 0), 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase text-slate-500">Subtotal</p>
                                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format((float) ($order->subtotal ?? 0), 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase text-slate-500">Shipping</p>
                                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ number_format((float) ($order->shipping_charge ?? 0), 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold uppercase text-slate-500">Items</p>
                                    <p class="mt-1 text-lg font-semibold text-slate-900">{{ $order->orderItems->count() }}</p>
                                </div>
                            </div>

                            @if ($order->orderItems->isNotEmpty())
                                <div class="mt-6 border-t border-slate-100 pt-4">
                                    <p class="text-sm font-semibold text-slate-900">Order Items</p>
                                    <div class="mt-3 space-y-2">
                                        @foreach ($order->orderItems as $item)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-slate-600">{{ $item->product_name ?? 'Product' }} (Qty: {{ $item->quantity }})</span>
                                                <span class="font-medium text-slate-900">{{ number_format((float) ($item->unit_price ?? 0) * $item->quantity, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Order Footer -->
                        <div class="border-t border-slate-100 px-6 py-4">
                            <a href="{{ route('user.order.details', ['id' => $order->id]) }}" class="text-sm font-medium text-green-600 transition hover:text-green-700">
                                View Details →
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination (if needed) -->
            @if ($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
