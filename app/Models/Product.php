<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function content()
    {
        return $this->hasMany(ProductContent::class);
    }
    public function sliderImage()
    {
        return $this->hasMany(SliderImage::class,'item_id','id')->where('item_type','product');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class,'product_id','id');
    }
}
