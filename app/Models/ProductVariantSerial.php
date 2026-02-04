<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantSerial extends Model
{
    use HasFactory;

    protected $table = 'variant_serials';

    protected $fillable = [
        'variant_id',
        'serial',
        'status',
    ];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
