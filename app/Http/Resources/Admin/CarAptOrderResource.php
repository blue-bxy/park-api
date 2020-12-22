<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CarAptOrderResource extends JsonResource
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
            'id' => $this->id,
            'park_name' => $this->parks->project_name,
            'no' => $this->order_no,

            'amount' => $this->total_amount,
            'deduct_amount' => $this->deduct_amount,    // 预约实际扣款
            'divide_amount' => $this->divide->platform_fee ?? 0,    // 平台手续费
            'user' => $this->whenLoaded('user', function () {
                return [
                    'user_id' => $this->user->id,
                    'nickname' => $this->user->nickname,
                    'mobile' => $this->user->mobile,
                ];
            }),
            // 'orders' => $this->orders->map(function ($order) {
            //     return [
            //         'paid_at' => $order->paid_at,
            //         'transaction_id' => $order->transaction_id
            //     ];
            // }),
            // 入场支付时间
            'paid_at' => ($order = $this->orders->first()) ? $order->paid_at->format('Y-m-d H:i') : null,
            'transaction_id' => $this->orders->map->transaction_id,
            'service_charge' => $this->orders->sum('service_charge') ?? null,

            'apt_car_num' => $this->userCar->car_number ?? null,

//            'stops' => $this->whenLoaded('carStop', function () {
//                return [
//                    'car_in_time' => $this->carStop->car_in_time->format('Y-m-d H:i'),
//                    'car_stop_num' => $this->carStop->userCar->car_number ?? null
//                ];
//            }),

            'car_in_time' => $this->userOrder ? ($this->userOrder->carStop ? $this->userOrder->carStop->car_in_time->format('Y-m-d H:i'): null) : null,

            'refund_amount' => $this->refund_total_amount,

            'car_rent_type' => $this->carRent->rent_type ?? null,
            'park_space_num' =>$this->parkSpace->number ?? null
        ];
    }
}
