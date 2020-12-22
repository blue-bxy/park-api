<?php


namespace App\Packages\Devices\DingDing;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $app)
    {
        $app['access_token'] = function ($app) {
            return new AccessToken($app);
        };

        $app['park_lock'] = function ($app) {
            return new ParkLockClient($app);
        };

        $app['hub_mac'] = function ($app) {
            return new HubMacClient($app);
        };

        $app['url'] = function ($app) {
            return new UrlClient($app);
        };
    }
}
