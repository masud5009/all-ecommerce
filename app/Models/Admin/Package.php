<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'icon',
        'title',
        'slug',
        'price',
        'term',
        'trial_days',
        'is_trial',
        'is_featured',
        'status',
        'recomended',
        'features',
        'custom_feature'
    ];


    /*============================
     Store package from request
     =============================*/
    public static function storeFromRequest($request)
    {
        if (!isset($request->featured)) {
            $request['featured'] = '0';
        }
        $features = json_encode($request->features);
        return self::create(
            $request->except('features') + [
                'slug' => createSlug($request->title),
                'features' => $features,
            ]
        );
    }

    /*============================
     Update package from request
     =============================*/
    public function updateFromRequest($request)
    {
        if (!isset($request->featured)) {
            $request['featured'] = '0';
        }

        $features = json_encode($request->features);

        $this->update(
            $request->except('features') + [
                'slug' => createSlug($request->title),
                'features' => $features,
            ]
        );

        return $this;
    }

}
