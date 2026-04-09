<?php

namespace App\Models;

use App\Models\ProductContent;
use App\Models\ProductSubcategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategory extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'serial_number', 'status', 'language_id', 'unique_id', 'icon'];

    /**
     * Get the count of products in this category for a specific language.
     */
    public function productContent()
    {
        return $this->hasMany(ProductContent::class, 'category_id');
    }

    public function subcategories()
    {
        return $this->hasMany(ProductSubcategory::class, 'category_id');
    }
}
