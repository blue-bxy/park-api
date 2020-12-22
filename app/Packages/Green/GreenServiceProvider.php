<?php

namespace App\Packages\Green;

use Illuminate\Support\ServiceProvider;

class GreenServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->singleton('green', function ($app) {
            $config = array_replace_recursive(config('aliyun.drivers.green'), config('green'));

            return new GreenService($config['key'], $config['secret'], $config['regionId']);
        });

        $this->app->alias('green', GreenService::class);
    }

    protected function registerConfig()
    {
        $configPath = __DIR__ .'/config.php';

        $this->publishes([$configPath => config_path('green.php')], 'config');

        $this->mergeConfigFrom($configPath, 'green');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
