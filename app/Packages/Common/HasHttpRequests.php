<?php


namespace App\Packages\Common;


use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

trait HasHttpRequests
{
    /**
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var \GuzzleHttp\HandlerStack
     */
    protected $handlerStack;

    /**
     * @var array
     */
    protected static $defaults = [
        'curl' => [
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ],
    ];

    public function getHttpClient()
    {
        return new Client();
    }

    /**
     * request
     *
     * @param string $url
     * @param string $method
     * @param array $options
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function request(string $url, $method = 'GET', $options = [])
    {
        $method = strtolower($method);

        $options = array_merge(self::$defaults, $options, ['handler' => $this->getHandlerStack()]);

        if (property_exists($this, 'base_uri') && !is_null($this->base_uri)) {
            $options['base_uri'] = $this->base_uri;
        }

        $response = $this->getHttpClient()->request($method, $url, $options);

        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();
        $response->getBody()->rewind();

        $array = json_decode($content, true, 512, JSON_BIGINT_AS_STRING);

        if (JSON_ERROR_NONE === json_last_error()) {
            return (array) $array;
        }

        return [];
    }

    /**
     * Build a handler stack.
     *
     * @return \GuzzleHttp\HandlerStack
     */
    public function getHandlerStack(): HandlerStack
    {
        if ($this->handlerStack) {
            return $this->handlerStack;
        }

        $this->handlerStack = HandlerStack::create($this->getGuzzleHandler());

        foreach ($this->middlewares as $name => $middleware) {
            $this->handlerStack->push($middleware, $name);
        }

        return $this->handlerStack;
    }

    /**
     * Get guzzle handler.
     *
     * @return callable
     */
    protected function getGuzzleHandler()
    {
        if (property_exists($this, 'app') && isset($this->app['guzzle_handler'])) {
            return is_string($handler = $this->app->raw('guzzle_handler'))
                ? new $handler()
                : $handler;
        }

        return \GuzzleHttp\choose_handler();
    }
}
