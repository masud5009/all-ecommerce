<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Language;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ShippingChargeController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::where('code', $request->language)->select('code', 'id', 'name')->firstOrFail();
        $datas = ShippingCharge::where('language_id', $language->id)->orderBy('serial_number', 'ASC')->get();
        return view('admin.shipping-charge.index', compact('datas', 'language'));
    }


    public function store(Request $request)
    {
        $rules = [
            'charge' => 'required|max:255',
            'serial_number' => 'required',
            'text' => 'required'
        ];

        $languages = app('languages');
        $messages = [];
        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required|max:255';
            $messages[$language->code . '_title.required'] = __('The title field is required for') . ' ' . $language->name . ' ' . __('language.');
        }


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }


        $index_id = uniqid();
        foreach ($languages as $language) {
            $data = new ShippingCharge();
            $data->unique_id = $index_id;
            $data->language_id = $language->id;
            $data->title = $request[$language->code . '_title'];
            $data->text = $request->text;
            $data->charge = $request->charge;
            $data->serial_number = $request->serial_number;
            $data->save();
        }
        session()->flash('success', __('Created successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function edit(Request $request, $id)
    {
        $language = Language::where('code', $request->language)->select('code', 'id', 'name')->firstOrFail();
        $data = ShippingCharge::findOrFail($id);
        return view('admin.shipping-charge.edit', compact('data', 'language'));
    }


    public function update(Request $request)
    {

        $rules = [
            'charge' => 'required|max:255',
            'serial_number' => 'required',
            'text' => 'required'
        ];

        $languages = app('languages');
        $messages = [];
        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required|max:255';
            $messages[$language->code . '_title.required'] = __('The title field is required for') . ' ' . $language->name . ' ' . __('language.');
        }


        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $sCharge = ShippingCharge::findOrFail($request->charge_id);
        $unique_id = is_null($sCharge->unique_id) ? uniqid() : $sCharge->unique_id;

        foreach ($languages as $language) {
            $data = ShippingCharge::where('id', $request[$language->code . '_id'])->first();
            if (empty($data)) {
                $data = new ShippingCharge();
            }
            $data->unique_id = $unique_id;
            $data->language_id = $language->id;
            $data->title = $request[$language->code . '_title'];
            $data->text = $request->text;
            $data->charge = $request->charge;
            $data->serial_number = $request->serial_number;
            $data->save();
        }
        session()->flash('success', __('Update successfully'));
        return response()->json(['status' => 'success'], 200);
    }



    public function delete(Request $request)
    {
        $charge_Id = $request->charge_id;
        $charge = ShippingCharge::findOrFail($charge_Id);

        $charge->delete();
        return redirect()->back()->with('success', __('Delete successfully'));
    }
    public function bulkdelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $category = ShippingCharge::findOrFail($id);
            $category->delete();
        }
        session()->flash('success', __('Delete successfully'));
        return response()->json(['status' => 'success'], 200);
    }
}
