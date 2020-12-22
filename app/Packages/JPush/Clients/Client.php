<?php


namespace App\Packages\JPush\Clients;


class Client
{
    protected $key;
    protected $secret;

    protected $retry;

    protected $zone = null;

    /**
     * Client constructor.
     * @param string $key
     * @param string $secret
     * @param int $retry
     * @param null $zone
     */
    public function __construct(string $key, string $secret, int $retry = 3, $zone = null)
    {
        $this->key = $key;

        $this->secret = $secret;

        $this->retry = $retry;

        $this->zone = $zone;
    }

    /**
     * httpPost
     *
     * @param string $url
     * @param array $data
     * @return array|mixed
     */
    protected function httpPost(string $url, array $data = [])
    {
        return $this->http()->post($url, $data)->json();
    }

    /**
     * httpGet
     *
     * @param string $url
     * @param null $query
     * @return array|mixed
     */
    protected function httpGet(string $url, $query = null)
    {
        return $this->http()->get($url, $query)->json();
    }

    /**
     * getSign
     *
     * @return string
     */
    protected function getSign()
    {
        return base64_encode($this->key.':'.$this->secret);
    }

    /**
     * http
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function http()
    {
        $http = \Http::withToken($this->getSign(), 'Basic');

        if (method_exists($this, 'baseUrl')) {
            $http->baseUrl($this->baseUrl());
        }

        return $http;
    }
}
