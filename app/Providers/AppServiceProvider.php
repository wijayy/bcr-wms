<?php

namespace App\Providers;

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
        View::composer('*', function ($view) {
            $view->with('format_number', function ($number, $decimal = 0) {
                return number_format(num: $number, decimals: $decimal,  decimal_separator: ',', thousands_separator: '.');
            });
        });
    }
}
