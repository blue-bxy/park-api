<?php

namespace App\Packages\Map;

use Illuminate\Support\ServiceProvider;

class MapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MapServer::class, function ($app) {
            $config = config('map');

            return new MapServer($config['client_id'], $config['secret']);
        });

        $this->app->alias(MapServer::class, 'map');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ .'/config.php';

        $this->publishes([$configPath => config_path('map.php')], 'config');

        $this->mergeConfigFrom($configPath, 'map');
    }
}
