<?php


namespace App\Packages\Map;


use Psr\Http\Message\RequestInterface;

class BaseClient
{
    protected $client_id;

    protected $secret;

    protected $base_url = "https://restapi.amap.com/v3/";

    protected $output = 'json';

    public function __construct($client_id, $secret = null)
    {
        $this->client_id = $client_id;

        $this->secret = $secret;
    }

    /**
     * client
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function client()
    {
        return \Http::baseUrl($this->base_url)->withMiddleware($this->identityMiddleware());
    }

    protected function getCredentials()
    {
        $credentials = [
            'key' => $this->client_id,
            'output' => $this->output
        ];

        if (!is_null($this->secret)) {
            $credentials['sig'] = $this->secret;
        }

        return array_filter($credentials);
    }

    public function identityMiddleware()
    {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $request = $this->applyToRequest($request, $options);

                return $handler($request, $options);
            };
        };
    }

    protected function applyToRequest(RequestInterface $request, array $options = [])
    {
        parse_str($request->getUri()->getQuery(), $query);

        $query = http_build_query(array_merge($this->getCredentials(), $query));

        return $request->withUri($request->getUri()->withQuery($query));
    }
}
