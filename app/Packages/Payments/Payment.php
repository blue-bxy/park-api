<?php

namespace App\Packages\Payments;


use App\Packages\Omnipay\Facades\Omnipay;
use App\Packages\Payments\Exceptions\OmninpayNotifyValidate;
use Omnipay\Common\Message\AbstractResponse;
use function GuzzleHttp\Psr7\parse_query;


class Payment
{
    /**
     * @var array
     */
    private static $supportChannel = [
        Config::DEFAULT_CHANNEL,
        Config::WX_CHANNEL_COMMON,
        Config::WX_CHANNEL_APP,
        Config::ALI_CHANNEL_APP,
        Config::ALI_CHANNEL_WAP,
        Config::ALI_CHANNEL_WEB,
    ];

    /**
     * 发起支付
     * @param $gateway
     * @param array $options
     * @return \Exception|mixed
     * @throws \Exception
     */
    public static function charge($gateway, array $options)
    {
        if (!in_array($gateway, self::$supportChannel)) {
            throw new \Exception('不支持的支付方式');
        }

        $charge = new Charge();

        $charge->initCharge($gateway, $options);

        $data = $charge->getData();

        $omnipay = Omnipay::gateway($gateway);

        // if (method_exists($gateway, 'setReturnUrl') && isset($options['refer'])) {
        //     //此处的地址应该协同前端确定
        //     $returnUrl = $options['refer'];
        //     $omnipay->setReturnUrl($returnUrl);
        // }

        $response = $omnipay->purchase($data)->send();

        if ($response->isSuccessful()) {
            switch ($gateway) {
                case Config::WX_CHANNEL_APP:
                    return $response->getAppOrderData();
                    break;
                case Config::ALI_CHANNEL_WAP:
                case Config::ALI_CHANNEL_WEB:
                    return $response->getRedirectUrl();
                    break;
                case Config::ALI_CHANNEL_APP:
                    return $response->getOrderString();
                //H5
                default:
                    return $response->getData();
            }
        }

        static::logs($options, $response->getData(), '支付调用失败');

        throw new \Exception($response->getData()['return_msg']);
    }


    public static function notify($gateway, $options)
    {
        if (!in_array($gateway, self::$supportChannel)) {
            throw new \Exception('不支持的支付方式');
        }

        /** @var \Omnipay\Common\AbstractGateway $omnipay */
        $omnipay = Omnipay::gateway($gateway);

        $request = $omnipay->completePurchase();
        // 微信与支付宝SDK有差异
        if (str_starts_with($gateway, Config::WX_CHANNEL_COMMON)) {
            $request->setRequestParams($options);
        } elseif (str_starts_with($gateway, 'ali')) {
            if (is_string($options)) {
                parse_str($options, $options);
            }
            $request->setParams($options);
        }

        /** @var AbstractResponse $response */
        $response = $request->send();

        $requestData = $response->getData();

        if ($response->isPaid()) {
            // only wechat
            if (method_exists($response, 'getRequestData')) {
                $requestData = $response->getRequestData();
            }
            static::logs([], $requestData, '支付回调处理');

            return static::instanceNotify($gateway, $requestData);
        }

        throw new OmninpayNotifyValidate('支付异步信息检验不通过', '-1');

    }

    public static function instanceNotify($gateway, array $options)
    {
        $notify = new Notify();

        $notify->initNotify($gateway, $options);

        return $notify->getData();
    }

    /**
     * 查询订单支付状态
     *
     * @param $gateway
     * @param $options
     * @return array
     * @throws \Exception
     */
    public static function query($gateway, $options)
    {
        /** @var \Omnipay\Common\AbstractGateway $omnipay */
        $omnipay = Omnipay::gateway($gateway);

        $options = is_string($options) ? ['order_no' => $options] : $options;

        $query = new Query();

        $query->initQuery($gateway, $options);

        $data = $query->getData();

        /** @var AbstractResponse $response */
        $response = $omnipay->query($data)->send();

        $result = $response->getData();

        $has_paid = $response->isPaid();

        static::logs($options, $result, '查询订单支付状态');

        return compact('has_paid', 'result');
    }

    /**
     * 退款 原路返回
     *
     * @param $gateway
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function refund($gateway, array $options)
    {
        if (! in_array($gateway, self::$supportChannel)) {
            throw new \Exception('不支持的支付方式');
        }

        $charge = new Refund();
        $charge->initRefund($gateway, $options);
        $data = $charge->getData();

        $response = Omnipay::gateway($gateway)
            ->refund($data)
            ->send();

        $result = $response->getData();

        $is_refund = $response->isSuccessful();

        static::logs($options, $result, '第三方退款');

        return compact('is_refund', 'result');
    }

    /**
     * 查询退款状态
     * @param $gateway
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function queryRefund($gateway, array $options)
    {
        if (!in_array($gateway, self::$supportChannel)) {
            throw new \Exception('不支持的支付方式');
        }

        $charge = new Refund();
        $charge->initRefund($gateway, $options);
        $data = $charge->getData();

        $omnipay = Omnipay::gateway($gateway);

        $query = str_starts_with($gateway, 'wx') ? 'queryRefund' : 'refundQuery';

        $response = $omnipay->{$query($data)}->send();

        $result = $response->getData();

        $is_refund = $response->isSuccessful();

        static::logs($options, $result, '查询退款状态');

        return compact('is_refund', 'result');
    }

    /**
     * 企业付款 目前只支持微信付款到零钱
     * @param $gateway
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public static function transfer(array $options, $gateway = Config::WX_CHANNEL_COMMON)
    {
        if (!str_starts_with($gateway, 'wx') || $gateway != Config::WX_CHANNEL_COMMON) {
            throw new \Exception('仅支持微信付款方式');
        }

        $charge = new Charge();
        $charge->initCharge($gateway, $options);
        $data = $charge->getData();

        /** @var \Omnipay\Common\AbstractGateway $omnipay */
        $omnipay = Omnipay::gateway($gateway);

        isset($data['openid']) ? $omnipay->transfer($data) : $omnipay->payBank($data);

        $response = $omnipay->send();

        $has_paid = $response->isSuccessful();

        $result = $response->getData();

        static::logs($options, $result, '企业付款');

        return compact('has_paid', 'result');
    }

    protected static function logs(array $options, array $result, $type = '')
    {
        paid_notify($options + [
                'result' => $result,
                'user' => auth()->user() ? auth()->user()->getAuthIdentifier() : null
            ],
            $type
        );
    }

    public static function getGateways()
    {
        return self::$supportChannel;
    }
}
