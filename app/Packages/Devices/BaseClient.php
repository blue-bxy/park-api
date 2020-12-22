<?php


namespace App\Packages\Devices;


use App\Packages\Common\HasHttpRequests;
use GuzzleHttp\Middleware;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class BaseClient
{
    use HasHttpRequests {request as performRequest; }

    protected $app;

    protected $base_uri;

    protected $middlewares = [];

    public function __construct(ServiceContainer $app)
    {
        $this->app = $app;
    }

    public function pushMiddleware(callable $middleware, string $name)
    {
        if (!is_null($name)) {
            $this->middlewares[$name] = $middleware;
        } else {
            array_push($this->middlewares, $middleware);
        }

        return $this;
    }

    public function httpGet(string $uri, array $query = [])
    {
        return $this->request($uri, 'GET', ['query' => $query]);
    }

    public function httpPost(string $uri, array $data = [], array $query = [])
    {
        return $this->request($uri, 'POST', [
            'json' => $data,
            'query' => $query
        ]);
    }

    public function request(string $url, string $method = 'GET', array $options = [])
    {
        if (empty($this->middlewares)) {
            $this->registerHttpMiddlewares();
        }

        return $this->performRequest($url, $method, $options);
    }

    protected function registerHttpMiddlewares()
    {
        // retry
        $this->pushMiddleware($this->retryMiddleware(), 'retry');
        // 身份标识
        $this->pushMiddleware($this->identityMiddleware(), 'access_token');
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

    protected function retryMiddleware()
    {
        return Middleware::retry(function (
            $retries,
            RequestInterface $request,
            ResponseInterface $response = null
        ) {
            // Limit the number of retries to 2
            if ($retries < $this->app->config->get('http.max_retries', 1) && $response && $body = $response->getBody()) {
                // Retry on server errors
                $response = json_decode($body, true);

                //Only DingDing
                if (!empty($response['errNo']) && in_array(abs($response['errNo']), [410, 427], true)) {
                    if (property_exists($this, 'app') && isset($this->app['access_token'])) {
                        $this->app['access_token']->refresh();
                    }
                    return true;
                }
            }

            return false;
        }, function () {
            return abs($this->app->config->get('http.retry_delay', 500));
        });
    }

    protected function applyToRequest(RequestInterface $request, array $options = [])
    {
        parse_str($request->getUri()->getQuery(), $query);

        $query = http_build_query(array_merge($this->getCredentials(), $query));

        return $request->withUri($request->getUri()->withQuery($query));
    }
}
