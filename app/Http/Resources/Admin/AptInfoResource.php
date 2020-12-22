<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AptInfoResource extends JsonResource
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
            'id' => $this->carApt->id,
            'park_name' => $this->carApt->parks->park_name,
            'order_no' => $this->carApt->userOrder->order_no,
            'car_num' => $this->carApt->userCar->car_number,
            'create_time' => $this->carApt->created_at,
            'apt_time' => $this->carApt->apt_time,
            'subscribe_amount' => $this->carApt->userOrder->subscribe_amount,
            'refund_amount' => $this->carApt->userOrder->refund_amount,
            'car_out_time' => $this->carApt->userOrder->carStop->car_out_time,
            'stop_time' => $this->carApt->userOrder->carStop->stop_time,
            'amount' =>$this->carApt->userOrder->amount,
            'payment_gateway' => $this->carApt->userOrder->payment_gateway,
            'discount_amount' => $this->carApt->userOrder->discount_amount,
            'special_price' => $this->carApt->userOrder->carStop->special_price,
            'washed_price' => $this->carApt->userOrder->carStop->washed_price,
        ];

    }
}
