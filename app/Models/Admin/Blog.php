<?php

namespace App\Models\Admin;

use App\Models\BlogContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'status', 'serial_number'];

    public function content()
    {
        return $this->hasMany(BlogContent::class,'blog_id','id');
    }
}
