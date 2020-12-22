<?php


namespace App\Packages\Devices\BeeFind;


use App\Packages\Devices\ServiceContainer;

/**
 * Class Application
 * @package App\Packages\Devices\Bee
 *
 * @property DeviceClient $device
 * @property BasicClient $basic
 */
class Application extends ServiceContainer
{
    protected $providers = [
        ServiceProvider::class
    ];
}
