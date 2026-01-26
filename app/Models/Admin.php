<?php

namespace App\Models;

use App\Models\Admin\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class Admin extends Model implements AuthenticatableContract
{
    use HasFactory, Authenticatable;
    protected $fillable = [
        'role',
        'name',
        'first_name',
        'last_name',
        'username',
        'password',
        'image',
        'role_id',
        'address',
        'email',
        'details',
        'status',
    ];

    protected $hidden = ['password'];

    public function adminRole()
    {
        return $this->belongsTo(Role::class, 'role', 'id');
    }
}
