<?php

namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class CarStopResource extends JsonResource
{
    public function toArray($request)
    {
        if($this->car_in_time){
            $car_in_time = $this->car_in_time->format('Y-m-d H:i');
        }else{
            $car_in_time = null;
        }

        if($this->car_out_time){
            $car_out_time = $this->car_out_time->format('Y-m-d H:i');
        }else{
            $car_out_time = null;
        }
        return [
            'id' => $this->id,
            'car_in_img' => $this->car_in_img,
            'car_out_img' => $this->car_out_img,
            'park_name' => $this->park->project_name ?? null,
            'car_num' => $this->car_num ? $this->car_num : ($this->userCar->car_number??null),
            'car_in_time' => $car_in_time,
            'car_out_time' => $car_out_time,
            'amount' => $this->userOrder->amount ?? 0,
            'free_price' => $this->userOrder->discount_amount ?? 0,
//            'free_type_id' => $this->userOrder->coupon_id,
            'special_price' => $this->special_price,
            'washed_price' => $this->washed_price,
        ];
    }
}
