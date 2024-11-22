<?php

namespace App\Providers;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;
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
        // Model::unguard();
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch->locales(['fr', 'en', 'de', 'nl']);
        });

        FilamentAsset::register([
            Js::make('stripe-url', 'https://js.stripe.com/v3/')->loadedOnRequest(),
        ]);
    }
}
