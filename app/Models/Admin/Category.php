<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'serial_number', 'status', 'language_id', 'unique_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
