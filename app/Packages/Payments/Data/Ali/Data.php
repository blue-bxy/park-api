<?php


namespace App\Packages\Payments\Data\Ali;


use App\Packages\Payments\Data\BaseData;

class Data extends BaseData
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
     * @param $value
     */
    public function setTransactionNo($value)
    {
        $this->values['trade_no'] = $value;
    }

    protected function unsetKeys(&$params)
    {
        foreach (['type', 'gateway'] as $key) {
            unset($params[$key]);
        }
    }

    public function toArray()
    {
        $params = $this->values;

        $this->unsetKeys($params);

        return [
            'biz_content' => $params
        ];
    }
}
