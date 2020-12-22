<?php

namespace App\Packages\Payments\Data\Ali;

class AliData extends Data
{
    /**
     * 设置商品价格 单位元
     * @param string $value
     **/
    public function setTotalAmount($value)
    {
        $this->values['total_amount'] = decimal_number($value/100);
    }

    /**
     * 设置商品或支付单简要描述
     * @param string $value
     **/
    public function setBody($value)
    {
        $this->values['subject'] = $value;
    }

    /**
     * 设置商品名称明细列表
     * @param string $value
     **/
    public function setDetail($value)
    {
        $this->values['body'] = $value;
    }

    /**
     * 设置附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
     * @param string $value
     **/
    public function setAttach($value)
    {
        if ($attach = $this->decode($value)) {
            $this->values['passback_params'] = urlencode(http_build_query($attach));
        }
    }

    /**
     * 用户付款中途退出返回商户网站的地址
     *
     * @param $value
     */
    public function setQuitUrl($value)
    {
        $this->values['quit_url'] = $value;
    }

    /**
     * 销售产品码，商家和支付宝签约的产品码
     *
     * @param $value
     */
    public function setProductCode($value)
    {
        $this->values['product_code'] = $value;
    }
}
