<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AptOrderResource extends JsonResource
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
            'id'=>$this->id,
            'order_no'=>$this->order_no,
            'settlement_type'=>$this->paid_at ? '正常' : '未支付',
            'apt_time'=>$this->carApts->apt_time ?? null,
            'apt_price'=>$this->carApts->total_amount ?? '0',
            'stop_time'=>$this->carStop->stop_time ?? null,
            'amount'=>$this->amount,
        ];
    }
}
