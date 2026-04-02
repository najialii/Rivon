<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;

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
        //
            LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en'])->circular()    ->flags([
                'ar' => asset('flags/saudi-arabia.svg'),
                // 'fr' => asset('flags/france.svg'),      
                'en' => asset('flags/usa.svg')
                ])
                ->flagsOnly();
        });
    }
}
