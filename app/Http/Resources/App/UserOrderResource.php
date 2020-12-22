<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderResource extends JsonResource
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
            'order_id' => $this->getKey(),
			'order_no' => $this->order_no,
            'status' => $this->status,
            'status_rename' => $this->status_rename,
            'has_came' => $this->hasCame(), // 是否进场
            'body' => $this->body, // 某某停车场、车位编号
            'subscribe_car_number' => $this->car->car_number,
            'amount' => $this->amount(), // 订单金额
            'time' => $this->carApts->apt_time ?? 0, // 预约时长
            'payment_no' => $this->payment_no,
            'expired' => $this->expired_at ? $this->expired_at->timestamp : '',
		];
    }
}
