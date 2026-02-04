<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantSoldSerial extends Model
{
    use HasFactory;

    protected $table = 'variant_sold_serials';

    protected $fillable = [
        'order_item_id',
        'variant_id',
        'serial',
        'status',
        'returned_at',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}
