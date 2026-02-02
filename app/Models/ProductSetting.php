<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductSetting extends Model
{
    use HasFactory;

    protected $table = 'product_settings';

    protected $fillable = [
        'digital_product',
        'physical_product',
    ];
}
