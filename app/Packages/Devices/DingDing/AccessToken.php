<?php


namespace App\Packages\Devices\DingDing;


use App\Packages\Common\HasHttpRequests;
use App\Packages\Devices\ServiceContainer;

class AccessToken
{
    use HasHttpRequests;

    /**
     * @var ServiceContainer
     */
    protected $app;

    protected $endpointToGetToken = "https://public.dingdingtingche.com/ddtcSDK/queryAccessToken";

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    public function refresh()
    {
        $this->getToken(true);

        return $this;
    }

    public function getToken(bool $refresh = false)
    {
        $cache_key = $this->getCacheKey();

        if (!$refresh && \Cache::has($cache_key)) {
            return \Cache::get($cache_key);
        }

        $token = $this->requestToken($this->getCredentials());

        $this->setToken($token['accessToken'], $token['expiresIn'] ?? 3000);

        return $token;
    }

    public function setToken($token, $lifetime = 3600)
    {
        \Cache::put($this->getCacheKey(), [
            'access_token' => $token,
            'expires_in' => $lifetime
        ], $lifetime - 500);

        return $this;
    }

    public function requestToken(array $credentials)
    {
        $token = $this->request($this->endpointToGetToken, 'GET', [
            'query' => $credentials
        ]);

        if ($token && isset($token['errNo']) &&  $token['errNo'] != 200) {
            throw new \Exception($token['errMessage'] ?? 'request token error');
        }

        return $token;
    }

    public function getCacheKey()
    {
        return md5(json_encode($this->getCredentials()));
    }

    public function getCredentials()
    {
        return [
            'appId' => $this->app['config']['client_id'],
            'appSecret' => $this->getSecret(),
        ];
    }

    protected function getSecret()
    {
        $secret = $this->app['config']['client_key'];

        return md5($secret);
    }

    public function getQuery()
    {
        return ['toBtoken' => $this->getToken()['access_token']];
    }
}
