<?php


namespace App\Packages\Devices\BeeFind;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['device'] = function ($app) {
            return new DeviceClient($app);
        };

        $app['basic'] = function ($app) {
            return new BasicClient($app);
        };
    }
}
