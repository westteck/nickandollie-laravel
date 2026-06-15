<?php

namespace App\Providers;

use App\Services\ThemeService;
use Illuminate\Support\Facades\View;
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
        // Inject theme colors into all views
        View::composer('*', function ($view) {
            $colors = ThemeService::getCurrentColors();
            $view->with('themeColors', $colors);
        });
    }
}
