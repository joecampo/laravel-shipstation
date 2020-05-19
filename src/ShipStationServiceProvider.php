<?php

namespace LaravelShipStation;

use Illuminate\Support\ServiceProvider;

class ShipStationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/config.php' => config_path('shipstation.php'),
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ShipStation::class, function ($app) {
            return new ShipStation(
                config('shipstation.apiKey'),
                config('shipstation.apiSecret'),
                config('shipstation.apiURL'),
                config('shipstation.partnerApiKey')
            );
        });
    }
}
