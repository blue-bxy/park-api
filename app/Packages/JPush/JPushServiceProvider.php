<?php

namespace App\Packages\JPush;

use App\Packages\JPush\Clients\DeviceClient;
use App\Packages\JPush\Clients\PushClient;
use App\Packages\JPush\Clients\ReportClient;
use Illuminate\Support\ServiceProvider;

class JPushServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ .'/config.php';

        $this->publishes([$configPath => config_path('services.php')], 'config');

        $this->mergeConfigFrom($configPath, 'services');

        $clients = [
            'report' => ReportClient::class,
            'device' => DeviceClient::class,
            'push' => PushClient::class
        ];

        foreach ($clients as $alias => $class) {
            $this->app->singleton("jpush.{$alias}", function ($app) use ($class) {
                $config = config('services.jpush');

                return new $class(
                    $config['key'],
                    $config['secret'],
                    $config['retry'] ?? 3
                );
            });

            $this->app->alias("jpush.{$alias}", $class);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerChannel();
    }

    protected function registerChannel()
    {
        $this->app->when(JPushChannel::class)
            ->needs(PushClient::class)
            ->give(function () {
                $config = config('services.jpush');

                return new PushClient(
                    $config['key'],
                    $config['secret'],
                    $config['retry'] ?? 3,
                    $config['zone']
                );
            });
    }
}
