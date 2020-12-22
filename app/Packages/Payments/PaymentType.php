<?php


namespace App\Packages\Payments;


class PaymentType
{
    const PAYMENT_TYPE_CHARGE = 'charge';

    const PAYMENT_TYPE_ORDER = 'order';

    const PAYMENT_TYPE_SUBSCRIBE_ORDER = 'subscribe';

    const PAYMENT_TYPE_SUBSCRIBE_REFUND = 'subscribe_refund';

    const PAYMENT_TYPE_SUBSCRIBE_CANCEL = 'subscribe_cancel';

    const PAYMENT_TYPE_SUBSCRIBE_RENEWAL_ORDER = 'subscribe_renewal';

    const PAYMENT_TYPE_WITHDRAW = 'withdraw';

    const PAYMENT_TYPE_AUTO_REFUND = 'auto_refund';

    public static $bodyMaps = [
        self::PAYMENT_TYPE_CHARGE => '充值',
        self::PAYMENT_TYPE_SUBSCRIBE_ORDER => '预约车位',
        self::PAYMENT_TYPE_SUBSCRIBE_CANCEL => '取消预约车位',
        self::PAYMENT_TYPE_SUBSCRIBE_RENEWAL_ORDER => '预约车位续费',
        self::PAYMENT_TYPE_WITHDRAW => '提现',
        self::PAYMENT_TYPE_AUTO_REFUND => '自动退款',
        self::PAYMENT_TYPE_SUBSCRIBE_REFUND => '预约退款',
    ];

    public static function getTypeNameBy($type)
    {
        return static::$bodyMaps[$type];
    }
}
