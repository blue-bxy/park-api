<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplyResource extends JsonResource
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
            'no'=>$this->no,
            'amount'=>$this->amount,
            'payment_number'=>$this->payment_number,
            'success_number'=>$this->success_number,
            'business_type'=>$this->business_type,
            'person_type'=>$this->person_type,
            'submit'=>$this->submit,
            'status'=>$this->status,
            'apply_time'=>$this->apply_time,
            'payment_time'=>$this->payment_time,
            'complete_time'=>$this->complete_time,
            'agent'=>$this->agent,
            'channel'=>$this->channel,
        ];
    }
}
