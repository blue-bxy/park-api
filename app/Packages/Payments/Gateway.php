<?php


namespace App\Packages\Payments;


class Gateway
{
    const CHANEL_TYPE_BALANCE = Config::DEFAULT_CHANNEL;
    const CHANEL_TYPE_WX      = 'wx';
    const CHANEL_TYPE_ALI     = 'ali';


    protected static $gateways = [
        Config::DEFAULT_CHANNEL,
        Config::WX_CHANNEL_APP,
        Config::ALI_CHANNEL_APP,
        Config::ALI_CHANNEL_WAP,
        Config::ALI_CHANNEL_NPD,
        Config::WX_CHANNEL_NPD
    ];

    public static $channelTypeMaps = [
        self::CHANEL_TYPE_BALANCE => '余额',
        self::CHANEL_TYPE_WX      => '微信',
        self::CHANEL_TYPE_ALI     => '支付宝',
    ];

    public static function getGateways()
    {
        return static::$gateways;
    }

    public static function getTypeName($type)
    {
        return array_first(self::$channelTypeMaps, function ($value, $key) use ($type) {
            return starts_with($type, $key);
        });
    }
}
