<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CarAptResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'park_name' => $this->parks->project_name ?? null,
            'apt_no' => $this->apt_no,
            'transaction_id' => $this->carAptOrder->transaction_id ?? null,
            'apt_price' => $this->apt_price,
            'paid_at' => $this->carAptOrder->paid_at ?? null,
            'car_num' => $this->car_num,
            'phone' => $this->phone,
            'car_in_time' => $this->userOrder->carStop->car_in_time ?? null,
            'refund_amount' => $this->userOrder->refund_amount ?? null,
        ];
    }
}
