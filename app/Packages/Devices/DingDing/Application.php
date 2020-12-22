<?php


namespace App\Packages\Devices\DingDing;


use App\Packages\Devices\ServiceContainer;

/**
 * Class Application
 * @package App\Packages\Devices\Bee
 *
 * @property AccessToken $access_token
 * @property ParkLockClient $park_lock
 * @property HubMacClient $hub_mac
 * @property UrlClient $url
 */
class Application extends ServiceContainer
{
    protected $providers = [
        ServiceProvider::class
    ];
}
