<?php


namespace App\Packages\Omnipay\Facades;

use Illuminate\Support\Facades\Facade;

class Omnipay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'omnipay';
    }
}
