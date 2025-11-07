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
    try {
        if (Schema::hasTable('settings')) {
            $settings = cache()->rememberForever('app_settings', function () {
                return DB::table('settings')->get();
            });

            foreach ($settings as $setting) {
                Config::set('constants.' . $setting->name, $setting->description);
            }
        }
    } catch (\Throwable $e) {
        // Skip during migration or DB connection error
        logger()->warning('AppServiceProvider skipped settings load: '.$e->getMessage());
    }
}

}
