@extends('frontend.user.layout')

@section('title', 'My Dashboard | FreshCart')
@section('page', 'user-dashboard')

@section('dashboard-content')
    <div class="bg-gradient-to-br from-emerald-50 via-white to-green-50 py-12">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 rounded-3xl border border-emerald-100 bg-white/90 p-6 shadow-xl shadow-emerald-100/40 sm:p-8">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-700">Welcome back</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">
                            Hello, {{ $user->username ?? 'Customer' }}
                        </h1>
                        <p class="mt-2 text-sm text-slate-600">
                            Manage your profile, track orders, and continue shopping from your dashboard.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Orders</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $stats['totalOrders'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Completed</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $stats['completedOrders'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Pending</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $stats['pendingOrders'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Cart Items</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ $stats['cartItems'] }}</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Spent</p>
                    <p class="mt-2 text-lg font-semibold text-slate-900">{{ number_format($stats['totalSpent'], 2) }}</p>
                </div>
            </div>

            <div class="mt-8 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-slate-900">Recent Orders</h2>
                    <span class="text-xs font-medium uppercase tracking-wide text-slate-500">Last 5 orders</span>
                </div>

                @if ($latestOrders->isEmpty())
                    <div
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-600">
                        You have no orders yet. Start shopping to see your orders here.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                                    <th class="py-3 pr-4">Order #</th>
                                    <th class="py-3 pr-4">Amount</th>
                                    <th class="py-3 pr-4">Order Status</th>
                                    <th class="py-3 pr-4">Payment</th>
                                    <th class="py-3">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($latestOrders as $order)
                                    <tr class="border-b border-slate-100 last:border-none">
                                        <td class="py-3 pr-4 font-medium text-slate-800">{{ $order->order_number ?? 'N/A' }}
                                        </td>
                                        <td class="py-3 pr-4 text-slate-700">
                                            {{ number_format((float) ($order->pay_amount ?? 0), 2) }}</td>
                                        <td class="py-3 pr-4">
                                            <span
                                                class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ strtolower((string) $order->order_status) === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ ucfirst((string) ($order->order_status ?? 'pending')) }}
                                            </span>
                                        </td>
                                        <td class="py-3 pr-4 text-slate-700">
                                            {{ ucfirst((string) ($order->payment_status ?? 'pending')) }}</td>
                                        <td class="py-3 text-slate-700">
                                            {{ optional($order->created_at)->format('d M Y, h:i A') }}</td>
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
