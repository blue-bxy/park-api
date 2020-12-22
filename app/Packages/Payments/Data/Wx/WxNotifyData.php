<?php


namespace App\Packages\Payments\Data\Wx;

use App\Packages\Payments\Data\BaseData;

/**
 * 微信支付回调 返回参数整理
 * Class WxNotifyData
 * @package App\Packages\Payments\Data\Wx
 */
class WxNotifyData extends BaseData
{
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
    public function setTotalFee($value)//total_fee
    {
        $this->values['total_amount'] = $value;
    }

    /**
     * @param $value
     */
    public function setBankType($value)
    {
        $this->values['bank_type'] = $value;
    }

    /**
     * @param $value
     */
    public function setFeeType($value)
    {
        $this->values['fee_type'] = $value;
    }

    /**
     * @param $value
     */
    public function setTransactionId($value)
    {
        $this->values['transaction_id'] = $value;
    }

    /**
     * @param $value
     */
    public function setOpenid($value)
    {
        $this->values['openid'] = $value;
    }

    /**
     * 支付完成时间
     * @param $value
     */
    public function setTimeEnd($value)
    {
        $this->values['time_end'] = $value;
    }

    /**
     * @param $value
     */
    public function setTradeType($value)
    {
        $this->values['trade_type'] = $value;
    }

    /**
     * @param $value
     */
    public function setAttach($value)
    {
        if ($attach = $this->decode($value)) {
            if (!empty($attach)) {
                foreach ($attach as $key => $val) {
                    $method = "set".ucfirst(camel_case($key));
                    if (method_exists($this, $method)) {
                        $this->$method($val);
                    }
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
