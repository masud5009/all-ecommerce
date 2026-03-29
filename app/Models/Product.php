<?php

namespace App\Models;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function content()
    {
        return $this->hasMany(\App\Models\ProductContent::class);
    }
    public function sliderImage()
    {
        return $this->hasMany(\App\Models\SliderImage::class, 'item_id', 'id')->where('item_type', 'product');
    }

    public function variations()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function options()
    {
        return $this->hasMany(\App\Models\ProductOption::class);
    }

    public function variants()
    {
        return $this->hasMany(\App\Models\ProductVariant::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\ProductReview::class);
    }
}
