<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RentalOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        switch ($this->status){
            case 'pending':
                $status = '待支付';
                break;
            case 'paid':
                $status = '已支付';
                break;
            case 'cancelled':
                $status = '已取消';
                break;
            case 'failed':
                $status = '已失败';
                break;
            case 'refunded':
                $status = '已退款';
                break;
            case 'finished':
                $status = '已完成';
                break;
            case 'commented':
                $status = '已评价';
                break;
        }

        return [
            'id'=>$this->id,
            'order_no'=>$this->order_no,
            'rent_no'=>$this->carApts->carRent->rent_no??null,
            'type'=>$status,
            'car_num'=>$this->carApts->userCar->car_number??null,
            'apt_time'=>$this->carApts->apt_time??null,
            'total_amount'=>$this->carApts->deduct_amount??null,
            'stop_time'=>$this->carStop->stop_time??null,
            'stop_price'=>$this->amount??null,
        ];
    }
}
