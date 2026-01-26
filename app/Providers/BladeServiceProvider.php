<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('sidebar', function ($expression) {
            return "<?php echo \\App\\Services\\SidebarService::getState({$expression}); ?>";
        });

        Blade::directive('sidebarMenu', function ($expression) {
            return "<?php echo \\App\\Services\\SidebarService::getState({$expression}, 'menu'); ?>";
        });
    }
}
