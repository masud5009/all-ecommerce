<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin\Language;
use App\Models\HomeSectionSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HomeSecController extends Controller
{
    public function index(Request $request)
    {
        $language_id = Language::where('code', $request->language)->first()->id;
        $data = HomeSectionSetting::where('language_id', $language_id)->first();
        return view('admin.home.imagetext', compact('data','language_id'));
    }

    public function update(Request $request)
    {
        $rules = [
            'category_title' => 'nullable|max:255',
            'featured_product_title' => 'nullable|max:255',
            'featured_product_subtitle' => 'nullable|max:255',
            'popular_product_title' => 'nullable|max:255',
            'popular_product_subtitle' => 'nullable|max:255',
            'flash_title' => 'nullable|max:255',
            'flash_subtitle' => 'nullable|max:255',
            'features_image' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg,avif|max:2048|max:255',
            'features_title' => 'nullable|max:255',
            'features_subtitle' => 'nullable|max:255',
            'features_text' => 'nullable|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray(),
            ], 422);
        }

        HomeSectionSetting::updateOrCreate(
            ['language_id' => $request->language_id],
            $request->except('_token', 'language_id')
        );

        session()->flash('success',__('Update successful'));
        return response()->json(['status' => 'success'], 200);
    }
}
