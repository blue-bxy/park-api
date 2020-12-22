<?php

namespace App\Packages\OAuth;

use App\Packages\Config;
use Illuminate\Http\Request;

class SocialiteManager
{
    protected $config;

    protected $gateways = [];

    protected $request;

    public function __construct(array $config, Request $request)
    {
        $this->config = new Config($config);

        $this->request = $request;
    }

    public function with($driver = null)
    {
        return $this->driver($driver);
    }

    public function driver($driver = null)
    {
        $driver = $driver ?: $this->getDefaultGateway();

        if (!isset($this->gateways[$driver])) {
            $this->gateways[$driver] = $this->createGateway($driver);
        }

        return $this->gateways[$driver];
    }

    protected function getDefaultGateway()
    {
        return $this->config->get('default');
    }

    protected function createGateway($driver)
    {
        $config = $this->config->get("gateways.".$driver);

        if (is_null($config)) {
            throw new \UnexpectedValueException("Gateway [$driver] is not defined.");
        }

        $class = __NAMESPACE__.'\\Providers\\'.$config['driver'].'Provider';

        if (!class_exists($class)) {
            throw new \UnexpectedValueException("Class '$class' not found");
        }

        return $this->buildProvider($class, $config);
    }

    protected function buildProvider($provider, $config)
    {
        return new $provider($this->request, $config);
    }
}
