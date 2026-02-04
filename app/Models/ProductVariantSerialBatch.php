<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantSerialBatch extends Model
{
    use HasFactory;

    protected $table = 'variant_serial_batches';

    protected $fillable = [
        'variant_id',
        'batch_no',
        'serial_start',
        'serial_end',
        'qty',
        'sold_qty',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
