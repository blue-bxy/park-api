<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RentalRecordResource extends JsonResource
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
            'record_id' => $this->getKey(),
            // 车位编号
            'carport_number' => $this->rent->rent_num,
            // 出租时间、出租价格、已租赁时长、租赁车牌、预期费用、实际费用、状态、下单日期
            'rental_time' =>  $this->rent->getRentalTime(),
            'rental_price' => $this->rent->rent_price,
            'use_time' => $this->getUseTime(),
            'car_number' => $this->car->car_number,
            'amount' => $this->getRentalAmount(),
            'status' => $this->status,
            'status_rename' => $this->status_rename,
            'lease_time' => $this->created_at->format('Y-m-d H:i'),
            'user_mobile' => $this->when($this->getHasPending(), function () {
                return $this->user->mobile;
            })
        ];
    }
}
