<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get all product contents
     */
    public function contents()
    {
        return $this->hasMany(ProductContent::class);
    }

    /**
     * Get content by language id
     */
    public function getContent($langId)
    {
        return $this->contents()->where('language_id', $langId)->first();
    }

    /**
     * Get single content (for eager loading)
     */
    public function content()
    {
        return $this->hasOne(ProductContent::class, 'product_id', 'id');
    }

    /**
     * Get category
     */
    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * get slider images
     */
    public function sliderImage()
    {
        return $this->hasMany(SliderImage::class, 'item_id', 'id')->where('item_type', 'product');
    }
}
