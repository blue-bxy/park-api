<?php


namespace App\Packages\OAuth\Providers;


use App\Packages\OAuth\AccessTokenInterface;

interface ProviderInterface
{
    public function user(AccessTokenInterface $token);
}
