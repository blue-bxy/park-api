<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
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
            'type'=>$this->type,
            'status'=>$this->status,
            'car_num'=>$this->car_num,
            'publish_type'=>$this->publish_type,
            'third_payment'=>$this->third_payment,
            'actual_price'=>$this->actual_price,
            'payed_at'=>$this->payed_at,
            'payment_method'=>$this->payment_method,
        ];
    }
}
