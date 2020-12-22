<?php


namespace App\Packages\OAuth;

use ArrayAccess;
use JsonSerializable;

class AccessToken implements AccessTokenInterface, ArrayAccess, JsonSerializable
{
    use HasAttributes;

    public function __construct(array $attributes)
    {
        if (empty($attributes['access_token'])) {
            throw new \InvalidArgumentException('"access_token" 不能为空');
        }

        $this->attributes = $attributes;
    }

    public function getToken()
    {
        return $this->getAttribute('access_token');
    }

    public function __toString()
    {
        return strval($this->getAttribute('access_token'));
    }

    public function jsonSerialize()
    {
        return $this->getToken();
    }

}
