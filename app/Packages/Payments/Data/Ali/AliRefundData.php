<?php

namespace App\Packages\Payments\Data\Ali;


class AliRefundData extends Data
{
    /**
     * 退款金额 单位元
     * @param string $value
     **/
    public function setRefundAmount($value)
    {
        $this->values['refund_amount'] = decimal_number($value / 100);
    }

    /**
     * 标识一次退款请求，同一笔交易多次退款需要保证唯一，如需部分退款，则此参数必传。
     * @param $value
     */
    public function setRefundNo($value)
    {
        $this->values['out_request_no'] = $value;
    }

    /**
     * 退款的原因说明
     *
     * @param $value
     */
    public function setReason($value)
    {
        $this->values['refund_reason'] = $value;
    }
}
