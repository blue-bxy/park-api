<?php


namespace App\Packages\Devices\Kernel\Providers;



use App\Packages\Devices\Kernel\Config;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['config'] = function ($app) {
            return new Config($app->getConfig());
        };
    }
}
