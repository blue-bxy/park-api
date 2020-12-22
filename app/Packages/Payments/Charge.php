<?php

namespace App\Packages\Payments;

use App\Packages\Payments\Data\Ali\AliData;
use App\Packages\Payments\Data\BaseInterface;
use App\Packages\Payments\Data\Wx\WxData;

class Charge
{
    protected $data;

    protected $aliProductCodes = [
        Config::ALI_CHANNEL_WAP => 'QUICK_WAP_WAY',
        Config::ALI_CHANNEL_APP => 'QUICK_MSECURITY_PAY',
        Config::ALI_CHANNEL_WEB => 'FAST_INSTANT_TRADE_PAY',
    ];

    public function initCharge($channel, array $options)
    {
        try {
            switch ($channel) {
                case Config::WX_CHANNEL_APP:
                case Config::WX_CHANNEL_COMMON:
                    $this->data = new WxData($options);
                    break;
                case Config::ALI_CHANNEL_WAP:
                case Config::ALI_CHANNEL_APP:
                case Config::ALI_CHANNEL_WEB:
                    $this->data = new AliData($options);
                    $this->data->setProductCode($this->aliProductCodes[$channel]);
                    break;
                default:
                    throw new \Exception('不支持的支付方式');
                    break;
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function getData()
    {
        if (!$this->data instanceof BaseInterface) {
            throw new \Exception('程序初始化错误'); //目的在于 统一输出格式
        }
        return $this->data->toArray();
    }
}
