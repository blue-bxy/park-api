<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'user_mobile' => $this->user->mobile ?? null,
            'order_no' => $this->order->no ?? null
        ];
        if ($request->routeIs('*.index')) {
            $data = array_merge($data, [
                'no' => $this->no,
                'user_nickname' => $this->user->nickname ?? null,
                'amount' => $this->amount,
                'distribution_method' => $this->distribution_method,
                'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null
            ]);
        } elseif ($request->routeIs('*.show')) {
            $data = array_merge($data, [
                'coupon_rule_title' => $this->coupon->rule->title ?? null,
                'max_amount' => $this->coupon->couponRule->amount ?? null,
                'valid_start_time' => $this->coupon->valid_start_time ?? null,
                'valid_end_time' => $this->coupon->valid_end_time ?? null,
                'province_id' => $this->coupon->couponParkRule->province_id ?? null,
                'city_id' => $this->coupon->couponParkRule->city_id ?? null,
                'district_id' => $this->coupon->couponParkRule->district_id ?? null,
                'park_id' => $this->park_id
            ]);
        }
        return $data;
    }
}
