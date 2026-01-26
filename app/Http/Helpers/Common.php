<?php

namespace App\Http\Helpers;

use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Validator;

class Common
{
    public static function variation_checkout_update($order_variations, $langId)
    {
        foreach ($order_variations as $variation) {

            $product_variation = ProductVariation::where([
                ['variant_name', $variation['variation_name']],
                ['product_id', $variation['product_id']],
                ['language_id', $langId]
            ])->first();

            if (!$product_variation) {
                continue;
            }

            //fetch all language variations
            $product_variation_all_lang = ProductVariation::where([
                ['variant_name', $variation['variation_name']],
                ['product_id', $variation['product_id']],
                ['indx', $product_variation->indx]
            ])->get();

            $v_op_name = json_decode($product_variation->option_name, true);
            $v_op_stock = json_decode($product_variation->option_stock, true);

            // find the key for the matching option
            $op_key = array_search($variation['option_name'], $v_op_name);

            if ($op_key === false) {
                continue;
            }

            //reduce stock for the specific option
            foreach ($product_variation_all_lang as $allLanV) {
                $v_op_stock = json_decode($allLanV->option_stock, true);
                if (isset($v_op_stock[$op_key])) {
                    $v_op_stock[$op_key] -= $variation['qty'];
                    $allLanV->option_stock = json_encode($v_op_stock);
                    $allLanV->save();
                }
            }
        }
    }

    //get variation product wise
    public static function getVariations($productId, $currentLang)
    {
        $variations =  ProductVariation::where([['product_id', $productId], ['language_id', $currentLang]])->get();
        return $variations;
    }
}
