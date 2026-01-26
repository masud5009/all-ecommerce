<?php

namespace App\Http\Middleware;

use App\Models\Admin\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminLangChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $code = Language::where('dashboard_default', 1)->pluck('code')->first();

        app()->setLocale('admin_' . $code);
        return $next($request);
    }
}
