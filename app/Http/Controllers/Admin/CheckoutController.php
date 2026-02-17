<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Common;
use App\Http\Helpers\MailConfig;
use App\Jobs\ProcessOrder;
use App\Jobs\SendOrderConfirmationEmail;
use App\Models\Admin\MailTemplate;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\Shipping\StedfastService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Str;
use PDF;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $cartData = session('posProduct', []);
        $cartTotal = session('posTotal', 0);

        $billing_details = session('billing_details', []);

        //if cart is empty
        if (empty($cartData) || $cartTotal <= 0) {
            return redirect()->back()->withErrors('Cart is empty or invalid.');
        }
        //if coupon applied

        $payAmount = ($cartTotal - (floatval($request->discount_amount) ?? 0)) + (floatval($request->tax_amount) ?? 0) + (floatval($request->shipping_amount) ?? 0);

        if ($payAmount < floatval($request->shipping_amount)) {
            session()->flash('warning', __('The cart total cannot be negative. Please review your cart and try again.'));
            return redirect()->back();
        }

        $hasPhysical = false;
        $productIds = collect($cartData)
            ->pluck('id')
            ->filter()
            ->unique()
            ->values();
        if ($productIds->isNotEmpty()) {
            $hasPhysical = Product::whereIn('id', $productIds)
                ->get(['type'])
                ->contains(function ($product) {
                    return strtolower((string)$product->type) === 'physical';
                });
        }

        $bs =  DB::table('settings')->select('currency_symbol', 'currency_text', 'currency_text_position', 'currency_symbol_position')->first();

        //if payment complete from pos
        $arrData = [
            'billing_name' => $billing_details ? $billing_details['billing_name'] : 'unknown',
            'billing_phone' => $billing_details ? $billing_details['billing_phone'] ?? 'unknown' : 'unknown',
            'billing_email' => $billing_details ? $billing_details['billing_email'] ?? 'unknown' : 'unknown',
            'billing_address' => $billing_details ? $billing_details['billing_address'] ?? 'unknown' : 'unknown',
            'billing_city' => $billing_details ? $billing_details['billing_city'] ?? null : null,
            'shipping_address' => $billing_details ? $billing_details['shipping_address'] ?? 'unknown' : 'unknown',
            'payment_method' => 'Cash Payment',
            'gateway' => 'Offline',
            'cart_total' => $cartTotal,
            'payAmount' => $payAmount,
            'discount_amount' => $request->discount_amount ?? 0,
            'tax' => $request->tax_amount ?? 0,
            'shipping_charge' => $request->shipping_amount ?? 0,
            'currency_symbol' => $bs->currency_symbol,
            'currency_symbol_position' => $bs->currency_symbol_position,
            'currency_text' => $bs->currency_text,
            'currency_text_position' => $bs->currency_text_position,
            'payment_status' => 'completed',
            'order_status' => 'completed',
            'receipt' => NULL,
            'delivery_date' => $billing_details['delivery_date'] ?? $request->delivery_date,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
        ];
        //save order
        $orderInfo = self::saveOrder($arrData, $cartData);

        ProcessOrder::dispatch($orderInfo, $cartData);

        $stedfastResult = StedfastService::createConsignment($orderInfo->fresh(), $hasPhysical);
        if (($stedfastResult['status'] ?? null) === 'error') {
            $warning = __('Order created but Stedfast booking failed.');
            if (!empty($stedfastResult['message'])) {
                $warning .= ' ' . $stedfastResult['message'];
            }
            session()->flash('warning', $warning);
        }

        //after save data all session forget
        session()->forget('posProduct');
        session()->forget('posTotal');
        session()->forget('billing_details');

        session()->flash('success', __('Order create successfully'));
        return redirect()->back();
    }

    /**
     * save orders
     */

    public static function saveOrder($arrData, $cartData)
    {
        $order = Order::create([
            'order_number' => generateOrderNumber(8),
            'billing_name' => $arrData['billing_name'],
            'billing_email' => $arrData['billing_email'],
            'billing_phone' => $arrData['billing_phone'],
            'billing_address' => $arrData['billing_address'],
            'billing_city' => $arrData['billing_city'] ?? null,
            'shipping_address' => $arrData['shipping_address'],
            'payment_method' => $arrData['payment_method'],
            'gateway' => $arrData['gateway'],
            'cart_total' => $arrData['cart_total'],
            'pay_amount' => $arrData['payAmount'],
            'discount_amount' => $arrData['discount_amount'],
            'tax' => $arrData['tax'],
            'shipping_charge' => $arrData['shipping_charge'] ?? 0,
            'invoice_number' => $arrData['invoice_number'] ?? null,
            'currency_symbol' => $arrData['currency_symbol'],
            'currency_symbol_position' => $arrData['currency_symbol_position'],
            'currency_text' => $arrData['currency_text'],
            'currency_text_position' => $arrData['currency_text_position'],
            'payment_status' => $arrData['payment_status'],
            'order_status' => $arrData['order_status'],
            'receipt' => $arrData['receipt'],
            'delivery_date' => $arrData['delivery_date'] ?? null,
        ]);

        foreach ($cartData as $item) {
            //get the order item variations
            $itemVariation = [];
            if (!empty($item['variations']) && is_array($item['variations'])) {
                foreach ($item['variations'] as $variation) {
                    $itemVariation[] = [
                        'product_id' => $item['id'],
                        'variation_id' => $variation['variation_id'],
                        'variation_name' => $variation['variation_name'],
                        'option_name' => $variation['option_name'],
                        'price' => $variation['price'],
                        'option_key' => $variation['option_key'],
                        'qty' => $item['quantity'] ?? 1
                    ];
                }
                //update stock
                Common::variation_checkout_update($itemVariation, app('defaultLang')->id);
            }

            //if product is physical then reduce stock
            $product = Product::find($item['id']);
            if (empty($item['variations']) && $product->type == 'Physical') {
                $product->stock -= $item['quantity'];
                $product->save();
            }

            //store order item
            OrderItem::create([
                'product_id' => $item['id'],
                'product_price' => $item['item_price'],
                'order_id' => $order->id,
                'qty' => $item['quantity'],
                'customer_id' => NULL,
                'variations' => json_encode($itemVariation),
            ]);
        }

        return $order;
    }

    /**
     * generate order invoice
     */

    public static function generateInvoice($orderInfo)
    {
        $data['bs'] = DB::table('settings')->select('website_logo', 'website_title', 'website_color')->first();
        $fileName = \Str::random(4) . time() . '.pdf';
        $dir = public_path('assets/front/invoices/product/');
        $path = $dir . $fileName;
        @mkdir($dir, 0777, true);
        $data['order']  = $orderInfo;
        $data['orderItems'] = OrderItem::where('order_id', $orderInfo->id)->get();
        $data['lang'] = app('defaultLang')->id;

        PDF::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'logOutputFile' => storage_path('logs/log.htm'),
            'tempDir' => storage_path('logs/')
        ])->loadView('admin.invoices.product-invoice', $data)->save($path);

        Order::where('id', $orderInfo->id)->update([
            'invoice_number' => $fileName
        ]);

        return $fileName;
    }

    /**
     * add billing informations for checkout
     */
    public function addBilling(Request $request)
    {
        if (empty(session()->get('posProduct'))) {
            session()->flash('warning', __("You can't add any product yet!"));
            return response()->json(['status' => 'success']);
        }
        $rules = [
            'billing_name' => 'required|string|max:255',
            'billing_phone' => 'required|string',
            'billing_email' => 'required|email|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'delivery_date' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        session()->put('billing_details', $request->all());

        session()->flash('success', __('Add successfully'));
        return response()->json(['status' => 'success']);
    }

    /**
     * order compleate mail send
     */

    public static function OrderMailSend($orderInfo, $invoice)
    {
        try {
            // get the mail template information from db
            $mailTemplate = MailTemplate::query()->where('type', '=', 'place_order')->first();
            $mailData['subject'] = $mailTemplate->subject;
            $mailBody = $mailTemplate->body;

            $pay_amount = currency_symbol_order($orderInfo->pay_amount, $orderInfo->currency_symbol, $orderInfo->currency_symbol_position);
            $quantiy = OrderItem::where('order_id', $orderInfo->id)->sum('qty');
            // $link = '<a href=' . url("user/signup-verify/" . $user->id) . '>Click Here</a>';

            $mailBody = str_replace('{customer_name}', $orderInfo->billing_name, $mailBody);
            $mailBody = str_replace('{order_number}', $orderInfo->order_number, $mailBody);
            $mailBody = str_replace('{date}', $orderInfo->created_at, $mailBody);
            $mailBody = str_replace('{price}', $pay_amount, $mailBody);
            $mailBody = str_replace('{quantiy}', $quantiy, $mailBody);
            $mailBody = str_replace('{website_title}', app('websiteSettings')->website_title, $mailBody);

            $mailData['invoice'] = public_path('assets/front/invoices/product/') . $invoice;
            $mailData['body'] = $mailBody;
            $mailData['recipient'] = $orderInfo->billing_email;
            MailConfig::send($mailData);
            return;
        } catch (Exception $e) {
            // dd($e);
        }
    }
}
