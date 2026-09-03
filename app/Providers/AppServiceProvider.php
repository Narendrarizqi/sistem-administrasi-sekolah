<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

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
        // Set locale aplikasi dan Carbon ke Bahasa Indonesia secara global
        config(['app.locale' => 'id']);
        config(['app.fallback_locale' => 'id']);
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'id', 'Indonesian', 'IND');

        // Gunakan Bootstrap 4 untuk pagination agar serasi dengan AdminLTE 3
        Paginator::useBootstrapFour();
    }
}
