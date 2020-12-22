<?php

namespace App\Http\Resources\App;

use App\Http\Resources\CarportMapResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderDetailResource extends JsonResource
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
            'order_id' => $this->getKey(),
            'order_no' => $this->order_no,
            'park_name' => $this->parks->park_name,
            'park_id' => $this->park_id,
            'subscribe_car_number' => $this->car->car_number,
            'add_time' => $this->created_at->format('Y-m-d H:i:s'),
            'status' => $this->status, // 行程状态
            'status_rename' => $this->status_rename,
            'fail_reason' => $this->getFailedReason(), // 失败原因
            'has_came' => $this->hasCame(), // 是否进场
            'longitude' => $this->parks->longitude,
            'latitude' => $this->parks->latitude,
            'maps' => $this->when($this->parks, function () {
                return new CarportMapResource($this->parks);
            }),
            'subscribe' => $this->when($this->carApts, function () {
                return [
                    'subscribe_time' => $this->carApts->apt_time,
                    'subscribe_amount' => $this->carApts->total_amount, // 总预约费用
                    'subscribed_at' => $this->carApts->created_at->format('Y-m-d H:i:s'),
                    // 收费标准
                    'subscribe_unit' => $this->carApts->getParkRateUnit(),
                    'subscribe_unit_amount' => $this->carApts->getParkRateAmount(),
                ];
            }),

            'park_space' => $this->when($space = $this->getParkSpace(), function () use($space) {
                return [
                    'park_space_id' => $space->getKey(),
                    'has_lock' => (bool) $space->locks_count,
                    'floor' => $space->floor,
                    'park_space_number' => $space->number,
                    'map_space_id' => $space->map_unique_id
                ];
            }),
            // 停车信息
            'stop' => $this->when($this->carStop, function () {
                return $this->carStop->info();
            }),
            // 订单信息
            'order' => [
                // 预约费用、停车费用、抵用券、应付金额、付款状态、交易方式、支付时间
                'subscribe_amount' => $this->subscribe_amount, // 预约费
                'amount' => $this->amount, // 停车费
                'coupon_amount' => 0,
                'paid_no' => $this->payment_no,
                'expired' => $this->expired_at ? $this->expired_at->timestamp : '',
                'paid_amount' => $this->amount + $this->subscribe_amount, // 总费用-预约费用-优惠券
                'has_paid' => $this->hasPaid(),
                $this->mergeWhen($this->hasPaid(), function () {
                    // 付款方式、付款时间
                    return [
                        'paid_gateway' => $this->payment_gateway,
                        'paid_gateway_name' => $this->payment_gateway_name,
                        'paid_time' => $this->paid_at->format('Y-m-d H:i:s')
                    ];
                }),
                // 退款信息
                'has_refund' => $this->hasRefund(),
                'refund' => $this->when($this->hasRefund(), function () {
                    return [
                        'refund_amount' => $this->refund->refunded_amount,
                        'has_refund' => $this->refund->hasRefund(),
                        'refunded_time' => $this->refund->created_at->format('Y-m-d H:i:s')
                    ];
                }),
            ],
            // 评价信息
            'comment' => $this->when($this->comment, function () {
                return [
                    'rate' => $this->comment->rate,
                    'content' => $this->comment->content,
                    'covers' => $this->comment->covers,
                    'comment_time' => $this->comment->created_at->format('Y-m-d H:i:s')
                ];
            })

        ];
    }
}
