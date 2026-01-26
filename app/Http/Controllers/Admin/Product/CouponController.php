<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index()
    {
        $data['coupons'] = ProductCoupon::latest('created_at')->get();
        return view('admin.product.coupon.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'code' => 'required|max:8|unique:product_coupons,code',
            'type' => 'required',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'amount_spend' => 'required|numeric|min:0',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }
        ProductCoupon::create($request->except(['_token']));

        session()->flash('success', __('Coupon create successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'code' => 'required|max:8|unique:product_coupons,code,' . $request->id,
            'type' => 'required',
            'value' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'amount_spend' => 'required|numeric|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $coupon = ProductCoupon::findOrFail($request->id);
        $coupon->update($request->except(['_token', '_method', 'id']));
        session()->flash('success', __('Coupon update successfully'));
        return response()->json(['status' => 'success'], 200);
    }
    public function delete(Request $request)
    {
        $cId = $request->coupon_id;
        $coupon = ProductCoupon::findOrFail($cId);

        $coupon->delete();
        return redirect()->back()->with('success', __('Coupon delete successfully'));
    }
    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $coupon = ProductCoupon::findOrFail($id);
            $coupon->delete();
        }
        session()->flash('success', __('Coupons delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }
}
