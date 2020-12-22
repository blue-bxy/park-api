<?php

namespace App\Packages\OAuth;

use Illuminate\Support\ServiceProvider;

class SocialiteProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SocialiteManager::class, function ($app) {
            return new SocialiteManager(config('oauth'), $app['request']);
        });

        $this->app->alias(SocialiteManager::class, 'socialite');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__ .'/config.php';

        $this->publishes([$configPath => config_path('oauth.php')], 'config');
    }

    public function provides()
    {
        return [SocialiteManager::class];
    }
}
