<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // dynamic constact varable
        $settings = DB::table('settings')->get();
        foreach ($settings as $setting) {
            Config::set('constants.' . $setting->name, $setting->description);
            
        }
    }
}
