<?php


namespace App\Packages\Devices;


use App\Packages\Devices\Kernel\Config;
use App\Packages\Devices\Kernel\Providers\ConfigServiceProvider;
use Pimple\Container;

/**
 * Class ServiceContainer
 * @package App\Packages\Devices
 *
 * @property Config $config
 */
class ServiceContainer extends Container
{
    protected $providers = [];

    protected $userConfig;

    protected $defaultConfig = [];

    public function __construct(array $config = [], array $prepends = [])
    {
        $this->registerProviders($this->getProviders());

        parent::__construct($prepends);

        $this->userConfig = $config;
    }

    public function getConfig()
    {
        $base = [
            'http' => [
                'timeout' => 30.0,
            ],
        ];

        return array_replace_recursive($base, $this->defaultConfig, $this->userConfig);
    }

    public function getProviders()
    {
        return array_merge([
            ConfigServiceProvider::class
        ], $this->providers);
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            parent::register(new $provider());
        }
    }
}
