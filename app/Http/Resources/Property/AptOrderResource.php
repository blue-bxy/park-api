<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class AptOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->carApt->carRent->rent_type_id==1){
            return [
                'id'=>$this->id,
                'order_no'=>$this->no,
                'car_num'=>$this->carApt->car->car_number??null,
                'subscribe_time'=>$this->subscribe_time,
                'amount'=>$this->amount,
                'created_at'=>$this->created_at->format('Y-m-d')
            ];
        }
    }
}
