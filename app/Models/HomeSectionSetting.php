<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSectionSetting extends Model
{
    protected $table = 'home_section_settings';

    protected $fillable = [
        'section',
        'key',
        'value',
        'type',
    ];
}
