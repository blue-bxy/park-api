<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SettleOrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        switch ($this->payment_gateway){
            case('balance'):
                $this->payment_gateway = '余额';
                break;
            case('wx_app'):
                $this->payment_gateway = '微信';
                break;
            case('ali_app'):
                $this->payment_gateway = '支付宝';
                break;
        }

        switch ($this->status) {
            case 'pending':
                $status = '待支付';
                break;

            case 'paid':
                $status = '已支付';
                break;

            case 'cancelled':
                $status = '已取消';
                break;

            case 'refunded':
                $status = '已退款';
                break;

            case 'failed':
                $status = '失败';
                break;

            default:
                $status = '已完成';
        }

        return [
            'id'=>$this->id,
            'user_name' => $this->user->nickname,
            'order_no'=>$this->order_no,
            'mobile'=>$this->user->mobile,
            'payment_gateway'=>$this->payment_gateway,
            'total_amount'=>$this->total_amount, //订单总金额金额
            'deduct_amount' => $this->carApts->deduct_amount,   // 预约实际扣款金额
            'status'=>$status,
        ];
    }
}
