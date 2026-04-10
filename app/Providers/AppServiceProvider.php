<?php

namespace App\Providers;

use App\Models\Footer;
use App\Models\Setting;
use App\Models\Admin\Language;
use App\Models\Admin\MenuBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Services\Payment\PaymentService::class);
        $this->app->bind(\App\Services\Payment\Gateways\PayPalGateway::class);
        $this->app->bind(\App\Services\Payment\Gateways\StripeGateway::class);
        $this->app->bind(\App\Services\Payment\Gateways\SslCommerzGateway::class);

        $this->app->singleton('languages', function () {
            return Language::all();
        });

        $this->app->singleton('websiteSettings', function () {
            return DB::table('settings')->select(
                'website_logo',
                'website_color',
                'maintenance_image',
                'maintenance_message',
                'website_title',
                'favicon',
                'currency_text',
                'currency_symbol',
                'currency_symbol_position',
                'currency_rate',
                'package_expire_day',
                'email_verification_approval',
                'facebook_pixel_status',
                'facebook_pixel_id',
                'google_recaptcha_status',
                'google_recaptcha_site_key',
                'google_recaptcha_secret_key',
                'google_analytics_status',
                'google_analytics_measurement_id'
            )->first();
        });

        $this->app->singleton('defaultLang', function () {
            return Language::where('dashboard_default', 1)->first();
        });

        $this->app->singleton('footerText', function () {
            return Footer::first();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        if (!app()->runningInConsole()) {
            /**
             * admin blade view
             */
            View::composer('admin.*', function ($view) {
                $time_zones_json_format = json_decode(file_get_contents(base_path('database/time_zone.json')), true);
                $timeZones = $time_zones_json_format['timezones'];
                $view->with([
                    'footerContent' => app('footerText'),
                    'defaultLang' => app('defaultLang'),
                    'timeZones' => $timeZones,
                    'languages' => app('languages'),
                ]);
            });

            /**
             * frontend blade view
             */
            View::composer('front.*', function ($view) {
                $websiteSettings = Setting::select(
                    'website_logo',
                    'favicon',
                    'website_title',
                    'website_color',
                    'facebook_pixel_status',
                    'facebook_pixel_id',
                    'google_recaptcha_status',
                    'google_recaptcha_site_key',
                    'google_recaptcha_secret_key',
                    'google_analytics_status',
                    'google_analytics_measurement_id',
                    'currency_text'
                )->first();

                if (session()->has('lang')) {
                    $currentLang = Language::where('code', session()->get('lang'))->first();
                } else {
                    $currentLang = Language::where('is_default', 1)->first();
                }

                $menus = [];
                $menu = MenuBuilder::where('language_id', $currentLang->id)->value('menu');
                $menus = json_decode($menu, true);


                $view->with([
                    'menus' => $menus,
                    'currentLang' => $currentLang,
                    'websiteInfo' => $websiteSettings,
                ]);
            });

            /**
             * user dashboard blade view
             */
            View::composer('user.*', function ($view) {
                /**
                 * for share data for user dashboard then use this middleware
                 * app/Http/Middleware/ShareUserData.php
                 * */
            });



            View::share(['websiteInfo' => app('websiteSettings')]);
        }
    }
}
