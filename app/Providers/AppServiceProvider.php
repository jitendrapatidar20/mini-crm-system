<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

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
     // Check if the 'settings' table exists before querying
    if (Schema::hasTable('settings')) {
        $settings = DB::table('settings')->get();

        foreach ($settings as $setting) {
            Config::set('constants.' . $setting->name, $setting->description);
        }
    }
}

}
