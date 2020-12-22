<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BadCreditResource extends JsonResource
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
            'order_no'=>$this->no,
            'mobile'=>$this->user->mobile??null,
            //'car_num'=>$this->user->cars->car_number??null,
            'order_amount'=>$this->amount,
            'bad_amount'=>$this->amount-$this->paid_amount,
            'already_amount'=>'',
            'is_payment'=>'',
            'bad_results'=>'',
            'bad_source'=>'',
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
