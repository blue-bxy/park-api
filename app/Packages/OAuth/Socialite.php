<?php


namespace App\Packages\OAuth;


use Illuminate\Support\Facades\Facade;

class Socialite extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SocialiteManager::class;
    }
}
