<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantValue extends Model
{
    use HasFactory;
     protected $fillable = ['variant_id', 'option_value_id'];

  public function variant() {
    return $this->belongsTo(ProductVariant::class, 'variant_id');
  }

  public function optionValue() {
    return $this->belongsTo(ProductOptionValue::class, 'option_value_id');
  }
}
