<?php

namespace App\Services;

class SidebarService
{
    public static function getState($expression, $type = 'active')
    {
        $routes = is_array($expression) ? $expression : [$expression];

        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return $type === 'active' ? 'active' : 'nav-item-expanded nav-item-open';
            }
        }

        return '';
    }
}
