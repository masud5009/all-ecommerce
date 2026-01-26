<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Admin\Language;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class VariantController extends Controller
{
    public function index($productId, Request $request)
    {
        $data['languages'] = app('languages');
        $data['productId'] = $productId;
        $languageId = Language::where('code', $request->language)->pluck('id');

        $variations = [];

        $information['ins'] = ProductVariation::where('product_id', $productId)
            ->where('language_id', $languageId)
            ->get();

        foreach ($information['ins'] as $key => $value) {
            $variations[] = ProductVariation::where('product_id', $productId)->where('indx', $value->indx)->get();
        }

        $data['variations'] = $variations;


        return view('admin.product.variation.index', $data);
    }

    public function store(Request $request)
    {
        // check if the variation helper data is null
        if (is_null($request->variation_helper)) {
            return "nullError";
        }
        // fetch languages from the app configuration or service.
        $languages = app('languages');
        $variation_helper = $request->variation_helper;


        // validation rules
        $rules = [];
        $messages = [];
        foreach ($variation_helper as $index) {
            foreach ($languages as  $language) {
                $code = $language->code;
                // Validate the variation name
                $rules["{$code}_variation_{$index}"] = 'required';
                $messages["{$code}_variation_{$index}.required"] = "The variant name is required for {$language->name} language.";

                // Validate option names, stock & price
                if (!empty($request[$code . '_options1_' . $index])) {
                    foreach ($request[$code . '_options1_' . $index] as $optionIndex => $option) {
                        $rules["{$code}_options1_{$index}.{$optionIndex}"] = 'required';
                        $rules["options2_{$index}.{$optionIndex}"] = 'required|numeric|min:0';
                        $rules["options3_{$index}.{$optionIndex}"] = 'required|integer|min:0';

                        $messages["{$code}_options1_{$index}.{$optionIndex}.required"] = "The option name is required for {$language->name} language.";
                        $messages["options2_{$index}.{$optionIndex}.required"] = "The price is required for the option.";
                        $messages["options2_{$index}.{$optionIndex}.numeric"] = "The price must be a number.";
                        $messages["options2_{$index}.{$optionIndex}.min"] = "The price must be at least 0.";
                        $messages["options3_{$index}.{$optionIndex}.required"] = "The stock is required for the option.";
                        $messages["options3_{$index}.{$optionIndex}.integer"] = "The stock must be an integer.";
                        $messages["options3_{$index}.{$optionIndex}.min"] = "The stock must be at least 0.";
                    }
                }
            }
        }

        //validation is here
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return Response::json(['errors' => $validator->getMessageBag()->toArray()], 422);
        }

        // delete any existing product variations for the specified product
        ProductVariation::where('product_id', $request->product_id)->delete();

        if (!empty($request->variation_helper)) {
            foreach ($variation_helper as $key => $v_helper) {
                foreach ($languages as $lkey => $value) {
                    if (!empty($request[$value->code . '_options1' . '_' .  $key])) {
                        foreach ($request[$value->code . '_options1' . '_' .  $key] as  $option) {
                            if (empty($option)) {
                                Session::flash('warning', __('Options are missing!'));
                                return "success";
                            }
                        }
                    } else {
                        Session::flash('success', __('Variations Updated successful'));
                        return "success";
                    }

                    ProductVariation::create([
                        'product_id' => $request->product_id,
                        'language_id' => $value->id,
                        'variant_name' => $request[$value->code . '_variation_' . $key],
                        'option_name' => json_encode($request[$value->code . '_options1' . '_' .  $key]),
                        'option_price' => json_encode($request['options2' . '_' .  $key]),
                        'option_stock' => json_encode($request['options3' . '_' .  $key]),
                        'indx' =>  $key
                    ]);
                }
            }
        }
        // deleting null data
        ProductVariation::where([['product_id', $request->product_id], ['variant_name', null]])->delete();
        Session::flash('success', __('Variations added successfully'));
        return "success";
    }
}
