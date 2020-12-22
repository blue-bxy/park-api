<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->route()->uri == 'property/discount/{discount}'){
            return [
                'title'=>$this->title,
                'coupon_rule_type'=>$this->coupon_rule_type,
                'coupon_rule_value'=>$this->coupon_rule_value,
                'max_receive_num'=>$this->max_receive_num,
                'quota'=>$this->quota,
                'start_time'=>$this->start_time,
                'end_time'=>$this->end_time,
            ];
        }
        return [
            'id'=>$this->id,
            'coupon_rule_type'=>$this->coupon_rule_type,
            'coupon_rule_value'=>$this->coupon_rule_value,
            'max_receive_num'=>$this->max_receive_num,
            'end_time'=>$this->end_time,
            'start_time'=>$this->start_time,
            'publisher'=>$this->publisher->name,
            'no'=>$this->no,
        ];
    }
}
