<?php

namespace App\Packages\Payments;



use App\Packages\Payments\Data\Ali\AliNotifyData;
use App\Packages\Payments\Data\BaseInterface;
use App\Packages\Payments\Data\Wx\WxNotifyData;

class Notify
{
    protected $data;

    public function initNotify($channel, array $options)
    {
        try {
            switch ($channel) {
                case Config::WX_CHANNEL_APP:
                case Config::WX_CHANNEL_COMMON:
                    $this->data = new WxNotifyData($options);
                    break;
                case Config::ALI_CHANNEL_WAP:
                case Config::ALI_CHANNEL_APP:
                case Config::ALI_CHANNEL_WEB:
                    $this->data = new AliNotifyData($options);
                    break;
                default:
                    break;
            }
        } catch (\Exception $exception) {
            return $exception;
        }

        return $this;
    }

    /**
     * getData
     *
     * @return mixed
     * @throws \Exception
     */
    public function getData()
    {
        if (!$this->data instanceof BaseInterface) {
            throw new \Exception('程序初始化错误'); //目的在于 统一输出格式
        }
        return $this->data->toArray();
    }
}
