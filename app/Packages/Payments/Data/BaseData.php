<?php

namespace App\Packages\Payments\Data;

abstract class BaseData implements BaseInterface
{
    protected $values = [];

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $property => $value) {
            $method = 'set' . ucfirst(camel_case($property));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    protected function decode($value)
    {
        $data = json_decode($value, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $data;
        }
        return false;
    }

    public function setGateway($value)
    {
        $this->values['gateway'] = $value;
    }

    public function setType($value)
    {
        $this->values['type'] = $value;
    }

    public function setOrderAddress($value)
    {
        $this->values['order_address'] = $value;
    }
}
