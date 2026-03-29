<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Membership;
use App\Models\FeaturedProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\Membership\MembershipService;

class PaymentHandler
{
    public static function handleSuccess($metadata)
    {
        DB::transaction(function () use ($metadata) {

            switch ($metadata->purpose) {
                case 'product':
                    Order::create([
                        'user_id' => $metadata->user_id,
                        'product_id' => $metadata->item_id,
                        'order_id' => $metadata->order_id,
                        'amount' => $metadata->payAmount,
                        'status' => 'paid',
                    ]);
                    // Extra: update inventory, notify seller
                    break;

                case 'featured':
                    return;
                    // Extra: schedule feature duration
                    break;
            }
        });
    }
}
