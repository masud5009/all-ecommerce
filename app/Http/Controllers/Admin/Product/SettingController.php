<?php

namespace App\Http\Controllers\Admin\Product;

use Illuminate\Http\Request;
use App\Models\ProductSetting;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function settings()
    {
        $data['product_setting'] = ProductSetting::first();
        return view('admin.product.settings', $data);
    }

    public function settingsUpdate(Request $request)
    {
        $product_setting = ProductSetting::first();
        if (!$product_setting) {
            $product_setting = new ProductSetting();
        }
        $product_setting->digital_product = $request->digital_product;
        $product_setting->physical_product = $request->physical_product;
        $product_setting->guest_checkout = $request->guest_checkout;
        $product_setting->contact_number = $request->contact_number;
        $product_setting->contact_number_status = $request->contact_number_status;
        $product_setting->whatsapp_number = $request->whatsapp_number;
        $product_setting->whatsapp_number_status = $request->whatsapp_number_status;
        $product_setting->save();
        return redirect()->back()->with('success', __('Updated successfully'));
    }
}
