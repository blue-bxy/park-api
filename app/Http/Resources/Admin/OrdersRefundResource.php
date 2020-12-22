<?php

namespace App\Http\Resources\Admin;

use App\Models\Dmanger\CarAptOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersRefundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->order instanceof CarAptOrder){
            return [
                'id'=>$this->id,
                'order_id'=>$this->order_id,
                'refund_no'=>$this->refund_no,
                'no'=>$this->order->no??null,
                'transaction_no'=>$this->order->transaction_id??null,
                'car_num'=>$this->order->carApt->car->car_number??null,
                'apt_time'=>$this->order->created_at->format('Y-m-d H:m:s')??null,
                'amount'=>$this->order->amount??null,
                'park_name'=>$this->order->carApt->parks->project_name??null,
                'refunded_amount'=>$this->refunded_amount??null,
                'refunded_at'=>date('Y-m-d H:i:s',strtotime($this->refunded_at)),
                'reason'=>$this->reason,
                'remarks'=>$this->remarks,
            ];
        }
    }
}
