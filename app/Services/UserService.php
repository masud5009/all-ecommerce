<?php

namespace App\Services;

use App\Models\User;
use App\Models\Membership;
use App\Models\User\UserLanguage;
use App\Models\User\UserSetting;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // delete membership
        Membership::where('user_id', $user->id)->delete();
        //delete user settings
        UserSetting::where('user_id', $user->id)->delete();

        UserLanguage::where('user_id', $user->id)->delete();

        // delete user
        $user->delete();
        return;
    }
}
