<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class CarStopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch($this->userOrder->payment_gateway ?? null){
            case 'wx_app':
                $this->userOrder->payment_gateway = '微信';
                break;
            case 'ali_app':
                $this->userOrder->payment_gateway = '支付宝';
                break;
            case 'balance':
                $this->userOrder->payment_gateway = '支付宝';
                break;
            default:
                null;
        }

        if($this->userOrder->carApts ?? null){
            $type = '预约停车';
        }else{
            $type = '非预约停车';
        }

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
            'car_in_time' => $car_in_time,
            'car_out_time' => $car_out_time,
            'car_num' => $this->car_num ? $this->car_num : ($this->userCar->car_number ?? null ),
            'stop_type' =>$type,
            'discount_amount' => $this->userOrder->discount_amount ?? 0,
            'pay_type' => $this->userOrder->payment_gateway ?? null,
            'amount' => $this->userOrder->amount ?? 0,
            'pay_time' => $this->userOrder ? $this->userOrder->paid_at->format('Y-m-d H:i'):null,
        ];
    }
}
