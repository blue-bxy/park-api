<?php


namespace App\Packages\Devices\UBer;


use App\Packages\Devices\ServiceContainer;

/**
 * Class Application
 * @package App\Packages\Devices\ParkLock
 *
 * @property Client $park_lock
 */
class Application extends ServiceContainer
{
    protected $providers = [
        ServiceProvider::class
    ];
}
