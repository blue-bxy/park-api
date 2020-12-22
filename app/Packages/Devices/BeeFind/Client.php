<?php


namespace App\Packages\Devices\BeeFind;


use App\Packages\Devices\BaseClient;

class Client extends BaseClient
{
    protected $queryName = 'clientId';

    protected function getCredentials()
    {
        return [
            'clientId' => $this->app['config']['client_id']
        ];
    }
}
