<?php

namespace App\Packages\Omnipay;

use Illuminate\Support\ServiceProvider;

class OmnipayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->setUpConfig();

        $this->app->singleton(OmnipayManager::class, function ($app){
            return new OmnipayManager($app->config->get('omnipay',[]));
        });

        $this->app->alias(OmnipayManager::class, 'omnipay');
    }

    protected function setUpConfig()
    {
        $configPath = __DIR__ .'/config.php';

        $this->publishes([$configPath => config_path('omnipay.php')], 'config');

        $this->mergeConfigFrom($configPath, 'omnipay');
    }

    public function provides()
    {
        return [OmnipayManager::class, 'omnipay'];
    }
}
