<?php


namespace App\Packages\Omnipay;


use App\Packages\Config;
use Omnipay\Common\Helper;
use Omnipay\Omnipay;

class OmnipayManager
{
    protected $config;

    protected $gateway;

    protected $gateways = [];

    public function __construct(array $config = [])
    {
        $this->config = new Config($config);
    }

    /**
     * gateway
     *
     * @param null $name
     * @return \Omnipay\Common\AbstractGateway
     */
    public function gateway($name = null)
    {
        $name = $name ?: $this->getGateway();

        if (!isset($this->gateways[$name])) {
            $this->gateways[$name] = $this->resolve($name);
        }

        return $this->gateways[$name];
    }

    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new \UnexpectedValueException("Gateway [$name] is not defined.");
        }

        $gateway = Omnipay::create($config['driver']);

        $class = trim(Helper::getGatewayClassName($config['driver']), "\\");

        $reflection = new \ReflectionClass($class);

        foreach ($config['options'] as $optionName => $value) {
            $method = 'set' . ucfirst($optionName);

            if ($reflection->hasMethod($method)) {
                $gateway->{$method}($value);
            }
        }

        return $gateway;
    }

    protected function getDefault()
    {
        return $this->config->get('default');
    }

    protected function getConfig($name)
    {
        return $this->config->get("gateways.{$name}");
    }

    public function getGateway()
    {
        if (!isset($this->gateway)) {
            $this->gateway = $this->getDefault();
        }
        return $this->gateway;
    }

    /**
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, array $args)
    {
        $callable = [$this->gateway(), $method];

        if (method_exists($this->gateway(), $method)) {
            return call_user_func_array($callable, $args);
        }
        throw new \BadMethodCallException("Method [$method] is not supported by the gateway [$this->gateway].");
    }
}
