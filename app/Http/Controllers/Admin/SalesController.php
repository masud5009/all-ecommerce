<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductExportReport;
use App\Exports\SalesExportReport;
use App\Http\Controllers\Controller;
use App\Models\Admin\Language;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $data['orders'] = Order::select('id', 'order_number', 'billing_name', 'payment_status', 'payment_method', 'order_status', 'invoice_number', 'receipt')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.sales.index', $data);
    }

    public function details($id)
    {
        $defaultLang = app('defaultLang');
        $data['order'] = Order::findOrFail($id);
        $data['order_items'] = OrderItem::with([
            'product.content' => function ($q) use ($defaultLang) {
                $q->where('language_id', $defaultLang->id);
            },
            'variant.variantValues.optionValue.option',
            'soldSerials',
        ])->where('order_id', $id)->get();
        $data['lang'] = $defaultLang->id;
        return view('admin.sales.details', $data);
    }

    public function report(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $paymentStatus = $request->payment_status;
        $orderStatus = $request->order_status;
        $paymentMethod = $request->payment_method;

        if (!empty($fromDate) && !empty($toDate)) {
            $orders = Order::when($fromDate, function ($query, $fromDate) {
                return $query->whereDate('created_at', '>=', Carbon::parse($fromDate));
            })->when($toDate, function ($query, $toDate) {
                return $query->whereDate('created_at', '<=', Carbon::parse($toDate));
            })->when($paymentMethod, function ($query, $paymentMethod) {
                return $query->where('payment_method', $paymentMethod);
            })->when($paymentStatus, function ($query, $paymentStatus) {
                return $query->where('payment_status', $paymentStatus);
            })->when($orderStatus, function ($query, $orderStatus) {
                return $query->where('order_status', $orderStatus);
            })->orderBy('id', 'DESC');

            Session::put('item_orders_report', $orders->get());
            $data['orders'] = $orders->paginate(10);
        } else {
            Session::put('item_orders_report', []);
            $data['orders'] = [];
        }



        return view('admin.sales.report', $data);
    }


    public function delete(Request $request)
    {
        $order_id = $request->order_id;
        $order = Order::findOrFail($order_id);

        OrderItem::where('order_id', $order->id)->delete();

        @unlink(public_path('assets/front/invoices/product/' . $order->invoice_number));

        $order->delete();
        return redirect()->back()->with('success', __('Order delete successfully'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $order = Order::findOrFail($id);
            OrderItem::where('order_id', $order->id)->delete();
            @unlink(public_path('assets/front/invoices/product/' . $order->invoice_number));
            $order->delete();
        }
        session()->flash('success', __('Orders delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }


    public function exportReport(Request $request)
    {
        $orderList = [];
        if (session()->has('item_orders_report')) {
            $orderList = session()->get('item_orders_report');
        } else {
            $currentLang = Language::where('code', $request->language)->select('id', 'code')->firstOrFail();
            $data['currentLang'] = $currentLang;

            $orderList = Order::orderBy('created_at', 'DESC')->get();
        }

        if (count($orderList) == 0) {
            session()->flash('warning', 'No order found to export!');
            return redirect()->back();
        } else {
            return Excel::download(new SalesExportReport($orderList), 'sales-list.csv');
        }
    }
    public function exportReportExcel(Request $request)
    {
        $orderList = [];
        if (session()->has('item_orders_report')) {
            $orderList = session()->get('item_orders_report');
        } else {
            $currentLang = Language::where('code', $request->language)->select('id', 'code')->firstOrFail();
            $data['currentLang'] = $currentLang;

            $orderList = Order::orderBy('created_at', 'DESC')->get();
        }

        if (count($orderList) == 0) {
            session()->flash('warning', 'No order found to export!');
            return redirect()->back();
        } else {
            return Excel::download(new SalesExportReport($orderList), 'sales-list.xlsx');
        }
    }
}
