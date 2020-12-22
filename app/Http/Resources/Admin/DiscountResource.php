<?php


namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'quota' => $this->quota,
            'coupon_rule_title' => $this->rules['rule']['title'] ?? null,
            'distribution_method' => $this->distribution_method,
            'start_time'            => $this->start_time ? $this->start_time->format('Y-m-d H:i:s') : null,
            'end_time'              => $this->end_time ? $this->end_time->format('Y-m-d H:i:s') : null,
            'valid_start_time'      => $this->valid_start_time ? $this->valid_start_time->format('Y-m-d H:i:s') : null,
            'valid_end_time'        => $this->valid_end_time ? $this->valid_end_time->format('Y-m-d H:i:s') : null,
        ];
        if ($request->routeIs('*.show')) {
            $data = array_merge($data, [
                'coupon_park_rule_title' => $this->rules['park']['title'] ?? null,
                'coupon_user_rule_title' => $this->rules['user']['title'] ?? null,
                'max_receive_num' => $this->max_receive_num,
                'distribution_method' => $this->distribution_method
            ]);
        }
        return $data;
//        return [
//            'id'                    => $this->id,
//            'title'                 => $this->title,
//            'park_name'             => $this->park->park_name,
//            'no'                    => $this->no,
//            'quota'                 => $this->quota,
//            'take_count'            => $this->take_count,
//            'used_count'            => $this->used_count,
//
//            'used_amount'           => $this->used_amount,
//            'max_receive_num'       => $this->max_receive_num,
//            'end_time'              => $this->end_time ? $this->end_time->format('Y-m-d H:i:s') : null,
//            'start_time'            => $this->start_time ? $this->start_time->format('Y-m-d H:i:s') : null,
//            'valid_start_time'      => $this->valid_start_time ? $this->valid_start_time->format('Y-m-d H:i:s') : null,
//            'valid_end_time'        => $this->valid_end_time ? $this->valid_end_time->format('Y-m-d H:i:s') : null,
//            'expired_time'          => $this->expired_at ? $this->expired_at->format('Y-m-d H:i:s') : null,
//
//            'coupon_rule_type'      => $this->coupon_rule_type,
//            'coupon_rule_value'     => $this->coupon_rule_value,
//            'coupon_rule_type_name' => $this->type_name,
//
//            'publisher'             => $this->publisher->name,
//            'use_scene'             => $this->use_scene,
//            'use_scene_name'        => $this->scene_name,
//
//        ];
    }
}
