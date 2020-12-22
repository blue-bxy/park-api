<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkIncomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        switch($this->payment_gateway){
            case 'wx_app':
                $this->payment_gateway = '微信';
                break;
            case 'ali_app':
                $this->payment_gateway = '支付宝';
                break;
            default:
                $this->payment_gateway = '余额';
        }
        return [
            'id' => $this->id,
            'user_name' => $this->user->nickname ?? null,
            'order_no' => $this->order_no,
            'refund_no' => $this->refund->refund_no ?? null,
            'apt_time' => $this->carApts ? $this->carApts->apt_start_time->format('Y-m-d H:i') : null,
            'car_in_time' =>  $this->carStop ? ($this->carStop->car_in_time ? $this->carStop->car_in_time->format('Y-m-d H:i') : null) : null,
            'car_out_time' => $this->carStop ? ($this->carStop->car_out_time ? $this->carStop->car_out_time->format('Y-m-d H:i') : null) : null,
            'car_num' => $this->car->car_number ?? null,
            'subscribe_amount' => $this->subscribe_amount,
            'deduct_amount' => $this->carApts->deduct_amount ?? null,
            'amount' =>$this->amount,
            'refunded_amount' => $this->refund->refunded_amount ?? null,
        ];
    }
}
