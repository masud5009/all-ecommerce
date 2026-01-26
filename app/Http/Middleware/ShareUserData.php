<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\View;

class ShareUserData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();

            $timeZones = Cache::rememberForever('timezones', function () {
                return json_decode(file_get_contents(base_path('database/time_zone.json')), true)['timezones'];
            });

            View::share([
                'defaultLang' => $user->currentLanguage,
                'authUser'    => $user,
                'timeZones'   => $timeZones,
                'languages'=> $user->languages,
            ]);
        }

        return $next($request);
    }
}
