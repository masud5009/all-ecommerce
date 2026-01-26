<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'blog_id',
        'category_id',
        'author',
        'title',
        'slug',
        'text',
        'meta_keyword',
        'meta_description'
    ];
}
