<?php

namespace App\Packages\Payments;

final class Config
{
    const DEFAULT_CHANNEL = 'balance';//余额支付

    const WX_CHANNEL_COMMON = 'wechat';// 微信 通用网关

    const WX_CHANNEL_NPD = 'wx_npd'; // 微信免密

    const WX_CHANNEL_APP = 'wx_app';// 微信 APP 支付

    const ALI_CHANNEL_APP = 'ali_app';// 支付宝 手机app 支付

    const ALI_CHANNEL_WAP = 'ali_wap'; // 支付宝 手机网页支付

    const ALI_CHANNEL_WEB = 'ali_web'; // 支付宝 PC

    const ALI_CHANNEL_NPD = 'ali_npd'; // 支付宝 免密
}
