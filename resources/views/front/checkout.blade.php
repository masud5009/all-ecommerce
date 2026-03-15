@extends('front.layout')

@section('title', 'Checkout | FreshCart')
@section('page', 'checkout')

@section('content')
    <div class="bg-gradient-to-b from-green-50/80 via-white to-white min-h-screen">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            {{-- Breadcrumb --}}
            <nav class="mb-8 flex flex-wrap items-center gap-2 text-xs text-slate-500" aria-label="Breadcrumb">
                <a href="{{ route('front.index') }}"
                    class="rounded-full border border-green-100 bg-white px-3 py-1 transition hover:border-green-300 hover:text-green-700">
                    Home
                </a>
                <span>/</span>
                <a href="{{ route('frontend.shop') }}"
                    class="rounded-full border border-green-100 bg-white px-3 py-1 transition hover:border-green-300 hover:text-green-700">
                    Shop
                </a>
                <span>/</span>
                <span class="rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                    Checkout
                </span>
            </nav>

            <h1 class="text-2xl font-semibold text-slate-900 sm:text-3xl">Checkout</h1>

            <div class="mt-8 grid gap-8 lg:grid-cols-[1fr_400px]">
                {{-- Left: Checkout Form --}}
                <div class="space-y-6">
                    <form id="paymentForm" class="space-y-6" method="POST" action="{{ route('cart.place.order') }}">
                        @csrf
                        {{-- Contact Information --}}
                        <div class="rounded-2xl border border-green-100 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Contact Information</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700" for="name">Full Name
                                        *</label>
                                    <input type="text" id="name" name="name" required
                                        class="mt-1 w-full rounded-xl border border-green-200 px-4 py-3 text-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                        placeholder="Your full name">
                                    <p class="text-sm text-red-500" id="err_name"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700" for="email">Email *</label>
                                    <input type="email" id="email" name="email" required
                                        class="mt-1 w-full rounded-xl border border-green-200 px-4 py-3 text-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                        placeholder="your@email.com">
                                    <p class="text-sm text-red-500" id="err_email"></p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700" for="phone">Phone Number
                                        *</label>
                                    <input type="tel" id="phone" name="phone" required
                                        class="mt-1 w-full rounded-xl border border-green-200 px-4 py-3 text-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                        placeholder="+880 1XXX-XXXXXX">
                                    <p class="text-sm text-red-500" id="err_phone"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Shipping Address --}}
                        <div class="rounded-2xl border border-green-100 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Shipping Address</h2>
                            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700"
                                        for="shipping_charge_id">Shipping Charge *</label>
                                    <select id="shipping_charge_id" name="shipping_charge_id"
                                        @if ($shippingOptions->isNotEmpty()) required @else disabled @endif
                                        class="mt-1 w-full rounded-xl border border-green-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-200 disabled:cursor-not-allowed disabled:bg-slate-100">
                                        @forelse ($shippingOptions as $option)
                                            <option value="{{ $option->id }}" @selected($loop->first)
                                                data-charge="{{ (float) $option->charge }}"
                                                data-formatted-charge="{{ currency_symbol($option->charge) }}"
                                                data-formatted-total="{{ currency_symbol($subtotal + (float) $option->charge) }}">
                                                {{ $option->title }} ({{ currency_symbol($option->charge) }})
                                            </option>
                                        @empty
                                            <option value="" data-charge="{{ (float) $shipping }}"
                                                data-formatted-charge="{{ currency_symbol($shipping) }}"
                                                data-formatted-total="{{ currency_symbol($subtotal + (float) $shipping) }}">
                                                Default Shipping ({{ currency_symbol($shipping) }})
                                            </option>
                                        @endforelse
                                    </select>
                                    <p class="text-sm text-red-500" id="err_shipping_charge_id"></p>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700" for="address">Address *</label>
                                    <textarea id="address" name="address" rows="2" required
                                        class="mt-1 w-full rounded-xl border border-green-200 px-4 py-3 text-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                        placeholder="House/Street/Area"></textarea>
                                    <p class="text-sm text-red-500" id="err_address"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700" for="city">City *</label>
                                    <input type="text" id="city" name="city" required
                                        class="mt-1 w-full rounded-xl border border-green-200 px-4 py-3 text-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                        placeholder="Dhaka">
                                    <p class="text-sm text-red-500" id="err_city"></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700" for="zip">Zip/Postal
                                        Code</label>
                                    <input type="text" id="zip" name="zip"
                                        class="mt-1 w-full rounded-xl border border-green-200 px-4 py-3 text-sm outline-none transition focus:border-green-500 focus:ring-2 focus:ring-green-200"
                                        placeholder="1205">
                                    <p class="text-sm text-red-500" id="err_zip"></p>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="rounded-2xl border border-green-100 bg-white p-6 shadow-sm">
                            <h2 class="text-lg font-semibold text-slate-900">Payment Method</h2>
                            <div class="mt-4 space-y-3">
                                <label
                                    class="group flex cursor-pointer items-center gap-4 rounded-xl border border-green-100 bg-white p-4 transition hover:border-green-300">
                                    <input type="radio" name="payment_method" value="cod" checked
                                        class="h-5 w-5 border-2 border-green-300 text-green-600 focus:ring-green-500">
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900">Cash on Delivery</p>
                                        <p class="text-xs text-slate-500">Pay when you receive your order</p>
                                    </div>
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path
                                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z">
                                        </path>
                                    </svg>
                                </label>
                                <label
                                    class="group flex cursor-pointer items-center gap-4 rounded-xl border border-green-100 bg-white p-4 transition hover:border-green-300">
                                    <input type="radio" name="payment_method" value="online"
                                        class="h-5 w-5 border-2 border-green-300 text-green-600 focus:ring-green-500">
                                    <div class="flex-1">
                                        <p class="font-semibold text-slate-900">Online Payment</p>
                                        <p class="text-xs text-slate-500">Pay securely via bKash, Nagad, or Card</p>
                                    </div>
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor"
                                        stroke-width="1.5" viewBox="0 0 24 24">
                                        <path
                                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z">
                                        </path>
                                    </svg>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Right: Order Summary --}}
                <div class="lg:sticky lg:top-24 h-fit">
                    <div class="rounded-2xl border border-green-100 bg-white p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-slate-900">Order Summary</h2>

                        {{-- Cart Items --}}
                        <div class="mt-4 max-h-80 space-y-4 overflow-y-auto">
                            @foreach ($cartItems as $item)
                                <div class="flex gap-4 border-b border-green-100 pb-4 last:border-0 last:pb-0">
                                    <div
                                        class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-green-100 bg-white">
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}"
                                            class="h-full w-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-slate-900 truncate">{{ $item['name'] }}</h4>
                                        @if (!empty($item['variant_label']))
                                            <p class="text-xs text-slate-500">{{ $item['variant_label'] }}</p>
                                        @endif
                                        <div class="mt-1 flex items-center justify-between">
                                            <span class="text-xs text-slate-500">Qty: {{ $item['quantity'] }}</span>
                                            <span
                                                class="text-sm font-semibold text-green-700">{{ currency_symbol($item['subtotal']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Totals --}}
                        <div class="mt-6 space-y-3 border-t border-green-100 pt-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Subtotal</span>
                                <span class="font-medium text-slate-900">{{ currency_symbol($subtotal) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Shipping</span>
                                <span id="shippingAmount"
                                    class="font-medium text-slate-900">{{ currency_symbol($shipping) }}</span>
                            </div>
                            <div class="flex items-center justify-between border-t border-green-100 pt-3">
                                <span class="text-base font-semibold text-slate-900">Total</span>
                                <span id="totalAmount"
                                    class="text-xl font-semibold text-green-700">{{ currency_symbol($total) }}</span>
                            </div>
                        </div>

                        {{-- Place Order Button --}}
                        <button type="button" id="paymentSubmitBtn"
                            class="mt-6 flex w-full items-center justify-center gap-2 rounded-2xl bg-green-600 px-6 py-4 text-base font-semibold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="btnText">Place Order</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/front/js/checkout.js') }}"></script>
@endsection
