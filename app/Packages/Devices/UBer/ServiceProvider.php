<?php


namespace App\Packages\Devices\UBer;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{

    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['park_lock'] = function ($app) {
            return new Client($app);
        };
    }
}
