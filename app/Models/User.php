<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\User\UserLanguage;
use App\Models\User\UserSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    protected $with = ['currentLanguage', 'languages', 'settings'];

    /**
     * Membership Relationship
     */
    public function memberships()
    {
        return $this->hasMany(Membership::class);
    }

    /**
     * Check if user has active membership
     */
    public function hasActiveMembership()
    {
        return $this->memberships()
            ->where('status', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where('expire_date', '>=', Carbon::now())
            ->exists();
    }

    /**
     * Get active membership details
     */
    public function getActiveMembership()
    {
        return $this->memberships()
            ->where('status', 1)
            ->where('start_date', '<=', Carbon::now())
            ->where('expire_date', '>=', Carbon::now())
            ->first();
    }

    /**
     * Scope for users with active membership
     */
    public function scopeWithActiveMembership($query)
    {
        return $query->whereHas('memberships', function ($q) {
            $q->where('status', 1)
                ->where('start_date', '<=', Carbon::now())
                ->where('expire_date', '>=', Carbon::now());
        });
    }
}
