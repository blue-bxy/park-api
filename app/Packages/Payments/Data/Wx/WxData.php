<?php


namespace App\Packages\Payments\Data\Wx;


use App\Packages\Payments\Data\BaseData;

class WxData extends BaseData
{
    /**
     * 设置商品订单号
     * @param string $value
     **/
    public function setOrderNo($value)
    {
        $this->values['out_trade_no'] = $value;
    }

    /**
     * 设置商品价格 单位分
     * @param string $value
     **/
    public function setTotalAmount($value)
    {
        $this->values['total_fee'] = $value;
    }

    /**
     * 设置商品或支付单简要描述
     * @param string $value
     **/
    public function setBody($value)
    {
        $this->values['body'] = $value;
    }

    /**
     * 设置商品名称明细列表
     * @param string $value
     **/
    public function setDetail($value)
    {
        $this->values['detail'] = $value;
    }

    /**
     * 设置APP和网页支付提交用户端ip，Native支付填调用微信支付API的机器IP。
     * @param string $value
     **/
    public function setClientIp($value)
    {
        $this->values['spbill_create_ip'] = $value;
    }

    /**
     * 设置币种
     * @param string $value
     **/
    public function setCurrency($value = 'CNY')
    {
        $this->values['fee_type'] = $value;
    }

    /**
     * @param $value
     */
    public function setOpenid($value)
    {
        $this->values['open_id'] = $value;
    }

    /**
     * 设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
     * @param string $value
     **/
    public function setAttach($value)
    {
        $this->values['attach'] = $value;
    }

    /**
     * 微信 流水号
     * @param $value
     */
    public function setTransactionNo($value)
    {
        $this->values['transaction_id'] = $value;
    }

    /**
     *  退款金额
     * @param $value
     */
    public function setRefundAmount($value)
    {
        $this->values['refund_fee'] = $value;
    }

    /**
     * @param $value
     */
    public function setRefundNo($value)
    {
        $this->values['out_refund_no'] = $value;
    }

    /**
     * 企业付款金额，单位为分
     * @param $value
     */
    public function setAmount($value)
    {
        $this->values['amount'] = $value;
    }

    /**
     * @param $value
     */
    public function setPartnerNo($value)
    {
        $this->values['partner_trade_no'] = $value;
    }

    /**
     * 校验用户姓名选项
     * NO_CHECK：不校验真实姓名
     * FORCE_CHECK：强校验真实姓名
     * @param string $value
     */
    public function setCheckName($value)
    {
        $this->values['check_name'] = $value;
    }

    /**
     * 收款用户姓名
     * 收款用户真实姓名。
     * 如果check_name设置为FORCE_CHECK，则必填用户真实姓名
     * @param $value
     */
    public function setUserName($value)
    {
        $this->values['re_user_name'] = $value;
    }

    /**
     * 收款方银行卡号
     *
     * @param $value
     */
    public function setBankNo($value)
    {
        $this->values['enc_bank_no'] = $value;
    }

    /**
     * 银行卡所在开户行编号
     *
     * @see https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=24_4
     *
     * @param $value
     */
    public function setBankCode($value)
    {
        $this->values['bank_code'] = $value;
    }

    /**
     * 收款方用户名
     *
     * @param $value
     */
    public function setTrueName($value)
    {
        $this->values['enc_true_name'] = $value;
    }

    /**
     * 企业付款描述信息
     * @param $value
     */
    public function setDesc($value)
    {
        $this->values['desc'] = $value;
    }

    public function toArray()
    {
        return $this->values;
    }
}
