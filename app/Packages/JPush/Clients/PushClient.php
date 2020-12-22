<?php

namespace App\Packages\JPush\Clients;


class PushClient extends Client
{
    protected $url = "https://api.jpush.cn/v3/push";

    /**
     * send
     *
     * @param array $params
     * @return array|mixed
     */
    public function send(array $params)
    {
        return $this->httpPost($this->url, $params);
    }
}
