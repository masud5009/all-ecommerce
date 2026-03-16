@extends('front.layout')

@section('title', 'Order Confirmed | FreshCart')
@section('page', 'order-success')

@section('content')
    <div class="bg-gradient-to-b from-green-50/80 via-white to-white min-h-screen">
        <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            {{-- Success Animation --}}
            <div class="text-center">
                <div class="relative mx-auto mb-6 inline-flex">
                    <div class="absolute inset-0 animate-ping rounded-full bg-green-200 opacity-75"></div>
                    <div class="relative rounded-full bg-green-100 p-6">
                        <svg class="h-16 w-16 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-slate-900 sm:text-4xl"> {{ __('Order Confirmed') }}!</h1>
                <p class="mt-3 text-lg text-slate-600">
                    {{ __("Thank you for your order. We're preparing it for delivery") }}</p>
            </div>

            {{-- Order Details Card --}}
            <div class="mt-10 rounded-2xl border border-green-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex flex-col items-center justify-between gap-4 border-b border-green-100 pb-6 sm:flex-row">
                    <div>
                        <p class="text-sm text-slate-500"> {{ __('Order Number') }}</p>
                        <p class="text-xl font-bold text-green-700">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="text-center sm:text-right">
                        <p class="text-sm text-slate-500"> {{ __('Order Date') }}</p>
                        <p class="font-medium text-slate-900">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>

                {{-- Items --}}
                <div class="mt-6">
                    <h3 class="font-semibold text-slate-900"> {{ __('Order Items') }}</h3>
                    <div class="mt-4 space-y-4">
                        @foreach ($order->items as $item)
                            @php
                                $variations = $item->variations ? json_decode($item->variations, true) : [];
                                $variantLabel = !empty($variations[0]['label']) ? $variations[0]['label'] : null;
                            @endphp
                            <div class="flex items-center gap-4 rounded-xl bg-green-50/50 p-3">
                                <div
                                    class="h-14 w-14 flex-shrink-0 overflow-hidden rounded-lg border border-green-100 bg-white">
                                    @if ($item->product)
                                        <img src="{{ asset('assets/img/product/' . $item->product->thumbnail) }}"
                                            alt="{{ $item->product->content->first()?->name ?? 'Product' }}"
                                            class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-slate-100">
                                            <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-sm font-medium text-slate-900 truncate">
                                        {{ $item->product?->content->first()?->name ?? 'Product' }}</h4>
                                    @if ($variantLabel)
                                        <p class="text-xs text-slate-500">{{ $variantLabel }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-slate-900">
                                        {{ currency_symbol($item->product_price) }} × {{ $item->qty }}</p>
                                    <p class="text-sm font-bold text-green-700">
                                        {{ currency_symbol($item->product_price * $item->qty) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Totals --}}
                <div class="mt-6 border-t border-green-100 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Subtotal</span>
                        <span class="font-medium text-slate-900">{{ currency_symbol($order->cart_total) }}</span>
                    </div>
                    <div class="mt-2 flex justify-between text-sm">
                        <span class="text-slate-600">Shipping</span>
                        <span class="font-medium text-slate-900">{{ currency_symbol($order->shipping_charge) }}</span>
                    </div>
                    <div class="mt-3 flex justify-between border-t border-green-100 pt-3">
                        <span class="font-semibold text-slate-900">Total</span>
                        <span class="text-xl font-bold text-green-700">{{ currency_symbol($order->pay_amount) }}</span>
                    </div>
                </div>

                {{-- Shipping Info --}}
                <div class="mt-6 grid gap-6 border-t border-green-100 pt-6 sm:grid-cols-2">
                    <div>
                        <h3 class="font-semibold text-slate-900">Shipping Address</h3>
                        <div class="mt-2 text-sm text-slate-600">
                            <p class="font-medium text-slate-900">{{ $order->billing_name }}</p>
                            <p>{{ $order->shipping_address }}</p>
                            <p class="mt-2">{{ $order->billing_phone }}</p>
                            <p>{{ $order->billing_email }}</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Payment Method</h3>
                        <div class="mt-2 flex items-center gap-2">
                            @if ($order->payment_method === 'Cash Payment')
                                <div class="rounded-lg bg-amber-100 p-2">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path
                                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125-1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-sm text-slate-600">Cash on Delivery</span>
                            @else
                                <div class="rounded-lg bg-blue-100 p-2">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path
                                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="text-sm text-slate-600">Online Payment</span>
                            @endif
                        </div>
                        <div class="mt-3">
                            <span
                                class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                <a href="{{ route('frontend.shop') }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-green-600 px-6 py-3 font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                        </path>
                    </svg>
                    Continue Shopping
                </a>
                <a href="{{ route('frontend.index') }}"
                    class="inline-flex items-center gap-2 rounded-2xl border border-green-200 bg-white px-6 py-3 font-semibold text-green-700 transition hover:-translate-y-0.5 hover:border-green-300 hover:shadow-md">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25">
                        </path>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
@endsection
