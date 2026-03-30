@extends('frontend.user.layout')

@section('title', 'My Dashboard | FreshCart')
@section('page', 'user-dashboard')

@section('dashboard-content')
    <div class="bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 rounded-3xl border border-emerald-100 bg-white/90 p-6 shadow-xl shadow-emerald-100/40 sm:p-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700"> {{ __('Welcome back') }}</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">
                            {{ $user->name ?? $user->username }}
                        </h1>
                        <p class="mt-2 text-sm text-slate-600">
                            {{ __('Manage your profile, track orders, and continue shopping from your dashboard.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <a href="{{ route('user.orders') }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 group-hover:text-green-600">
                        {{ __('Total Orders') }}</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 group-hover:text-green-700">
                        {{ $stats['totalOrders'] }}</p>
                </a>
                <a href="{{ route('user.orders', ['status' => 'completed']) }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 group-hover:text-green-600">
                        {{ __('Completed') }}</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 group-hover:text-green-700">
                        {{ $stats['completedOrders'] }}</p>
                </a>
                <a href="{{ route('user.orders', ['status' => 'pending']) }}"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 group-hover:text-green-600">
                        {{ __('Pending') }}</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 group-hover:text-green-700">
                        {{ $stats['pendingOrders'] }}</p>
                </a>
                <a href="" data-action="open-cart-offcanvas"
                    class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 group-hover:text-green-600">
                        {{ __('Cart Items') }}</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900 group-hover:text-green-700">
                        {{ $stats['cartItems'] }}</p>
                </a>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500"> {{ __('Total Spent') }}</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">
                        {{ currency_symbol($stats['totalSpent']) }}
                    </p>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900"> {{ __('Recent Orders') }}</h2>
                </div>

                @if ($latestOrders->isEmpty())
                    <div
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-600">
                        {{ __('You have no orders yet. Start shopping to see your orders here.') }}
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                                    <th class="py-3 pr-4"> {{ __('Order') }} #</th>
                                    <th class="py-3 pr-4"> {{ __('Amount') }}</th>
                                    <th class="py-3 pr-4"> {{ __('Order Status') }}</th>
                                    <th class="py-3 pr-4"> {{ __('Payment') }}</th>
                                    <th class="py-3 pr-4"> {{ __('Date') }}</th>
                                    <th class="py-3"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latestOrders as $order)
                                    <tr class="border-b border-slate-100 last:border-none">
                                        <td class="py-3 pr-4 font-medium text-slate-800">
                                            {{ $order->order_number ?? 'N/A' }}</td>
                                        <td class="py-3 pr-4 text-slate-700">
                                            {{ currency_symbol_order($order->pay_amount, $order->currency_symbol, $order->currency_symbol_position) }}
                                        </td>
                                        <td class="py-3 pr-4">
                                            <span
                                                class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ strtolower((string) $order->order_status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $order->order_status ?? 'pending' }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-4 text-slate-700">{{ $order->payment_status ?? 'pending' }}
                                        </td>
                                        <td class="py-3 pr-4 text-slate-700">
                                            {{ optional($order->created_at)->format('d M Y, h:i A') }}</td>
                                        <td class="py-3">
                                            <a href="{{ route('user.order.details', ['id' => $order->id]) }}"
                                                class="inline-flex items-center gap-1 rounded-md bg-green-100 px-3 py-1.5 text-xs font-medium text-green-700 transition hover:bg-green-200">
                                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
