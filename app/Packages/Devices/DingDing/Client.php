<?php


namespace App\Packages\Devices\DingDing;


use App\Packages\Devices\BaseClient;

class Client extends BaseClient
{
    protected $base_uri =  "http://182.92.99.168:18082/ddtcDingHub/";

    protected function getCredentials()
    {
        return $this->app['access_token']->getQuery();
    }

    public function httpGet(string $uri, array $query = [])
    {
        $response = $this->request($uri, 'GET', ['query' => $query]);

        $response['data'] = json_decode(isset($response['data']) ? $response['data'] : []);

        return $response;
    }
}
