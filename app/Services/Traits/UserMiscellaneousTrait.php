<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\Auth;

trait UserMiscellaneousTrait
{
    private function getCurrentLang()
    {
        return  Auth::guard('web')->user()->currentLanguage;
    }

    private function getUserLanguages()
    {
        return  Auth::guard('web')->user()->languages;
    }
}
