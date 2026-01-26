<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->paginate(10);

        return view('admin.transaction', compact('transactions'));
    }


    public static function product_purchase($orderInfo)
    {
        $prv_balance = Transaction::sum('actual_total');
        $after_balance = $prv_balance + $orderInfo->pay_amount;

        Transaction::create([
            'transaction_id' => $orderInfo->order_number,
            'pre_balance' => $prv_balance,
            'actual_total' => $orderInfo->pay_amount,
            'after_balance' => $after_balance,
            'currency_symbol' => $orderInfo->currency_symbol,
            'currency_symbol_position' => $orderInfo->currency_symbol_position,
            'payment_status' => $orderInfo->payment_status,
            'payment_method' => $orderInfo->payment_method,
            'transaction_type' => 'product_purchase',
        ]);
    }
}
