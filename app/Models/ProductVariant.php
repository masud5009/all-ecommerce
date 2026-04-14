<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'image',
        'price',
        'stock',
        'status',
        'show_on_card_price',
        'track_serial',
        'serial_start',
        'serial_end',
    ];

    protected $casts = [
        'status' => 'integer',
        'show_on_card_price' => 'integer',
        'track_serial' => 'integer',
        'stock' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variantValues()
    {
        return $this->hasMany(ProductVariantValue::class, 'variant_id');
    }

    public function serialBatches()
    {
        return $this->hasMany(ProductVariantSerialBatch::class, 'variant_id');
    }

    public function soldSerials()
    {
        return $this->hasMany(ProductVariantSoldSerial::class, 'variant_id');
    }
}
