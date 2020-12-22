<?php

namespace App\Packages\Devices;

use App\Packages\Devices\BeeFind\Application as BeeFind;
use App\Packages\Devices\DingDing\Application as DingDing;
use App\Packages\Devices\UBer\Application as UBer;
use Illuminate\Support\ServiceProvider;

class DevicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->setUpConfig();

        $apps = [
            'u_ber' => UBer::class,
            'bee_find' => BeeFind::class,
            'ding_ding' => DingDing::class,
        ];

        foreach ($apps as $name => $class) {
            $accounts = config('device.'.$name);

            foreach ($accounts as $account => $config) {
                $this->app->singleton("device.{$name}.{$account}", function ($app) use ($class, $config) {
                    $app = new $class(array_merge(config('device.defaults', []), $config));

                    return $app;
                });

                $this->app->alias("device.{$name}.{$account}", "device.{$name}");
            }

            $this->app->alias("device.{$name}.default", "device.{$name}");

            $this->app->alias("device.{$name}", $class);

        }
    }

    protected function setUpConfig()
    {
        $configPath = __DIR__ .'/config.php';

        $this->publishes([$configPath => config_path('device.php')], 'config');

        $this->mergeConfigFrom($configPath, 'device');
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
