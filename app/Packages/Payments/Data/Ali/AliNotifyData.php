<?php

namespace App\Packages\Payments\Data\Ali;

use App\Packages\Payments\Data\BaseData;

/**
 * 支付宝支付回调 返回参数整理
 * Class WxNotifyData
 * @package App\Packages\Payments\Data\Ali
 */
class AliNotifyData extends BaseData
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->values['origin_result'] = $attributes;
    }

    /**
     * @param $value
     */
    public function setOutTradeNo($value)//out_trade_no
    {
        $this->values['order_no'] = $value;
    }

    /**
     * 订单总金额 返回单位：元
     * @param $value
     */
    public function setTotalAmount($value)//total_amount
    {
        $this->values['total_amount'] = $value * 100;
    }

    /**
     * 实收金额
     * 商家在交易中实际收到的款项，单位为元
     * @param $value
     */
    public function setReceiptAmount($value) //receipt_amount
    {
        $this->values['receipt_amount'] = $value * 100;
    }

    /**
     * 付款金额
     * 用户在交易中支付的金额
     * @param $value
     */
    public function setBuyerPayAmount($value)
    {
        $this->values['buyer_pay_amount'] = $value;
    }

    /**
     * 支付宝交易号
     * @param $value
     */
    public function setTradeNo($value)
    {
        $this->values['transaction_id'] = $value;
    }

    /**
     * 支付完成时间
     * @param $value
     */
    public function setGmtPayment($value)
    {
        $this->values['time_end'] = $value;
    }

    /**
     * 支付金额信息
     * 支付成功的各个渠道金额信息
     * @param $value
     */
    public function setFundBillList($value)
    {
        $this->values['fund_bill_list'] = $value;
    }

    /**
     * @param $value
     */
    public function setPassBackParams($value)
    {
        parse_str(urldecode($value), $attach);

        if (!empty($attach)) {
            foreach ($attach as $key => $val) {
                $method = "set".ucfirst(camel_case($key));
                if (method_exists($this, $method)) {
                    $this->$method($val);
                }
            }
        }

        $this->values['attach'] = $attach ?? $value;
    }

    public function toArray()
    {
        return $this->values;
    }
}
