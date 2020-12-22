<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'refund_no'=>$this->refund_no,
            'order_no'=>$this->order->order_no,
            'car_num'=>$this->order->car_num,
            'car_out_time'=>$this->order->carstop->car_out_time,
            'actual_price'=>$this->order->actual_price,
            'project_name'=>$this->order->parks->project_name,
            'refunded_amount'=>$this->refunded_amount,
            'refunded_at'=>$this->refunded_at,
            'reason'=>$this->reason,
            'remark'=>$this->remark
        ];
    }
}
