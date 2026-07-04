<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Paksa semua URL menggunakan HTTPS saat di production (Render, dll)
        // Ini diperlukan karena Render menggunakan reverse proxy
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
