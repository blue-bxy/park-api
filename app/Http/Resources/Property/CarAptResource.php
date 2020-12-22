<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class CarAptResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        if($this->userOrder->status == 'pending'){
            $status = '待支付';
        }elseif ($this->userOrder->status == 'paid'){
            $status = '已支付';
        }elseif ($this->userOrder->status == 'cancelled'){
            $status = '已取消';
        }elseif ($this->userOrder->status == 'failed'){
            $status = '已失败';
        }elseif ($this->userOrder->status == 'refunded'){
            $status = '已退款';
        }elseif ($this->userOrder->status == 'finished'){
            $status = '已完成';
        }else{
            $status = '已评价';
        }

        $publisher_type = [1=>'物业',2=>'业主',3=>'云端'];

        return [
            'id' => $this->id,
            'total_amount' => $this->total_amount,
            // 入场支付时间
            'paid_at' => ($order = $this->orders->first()) ? $order->paid_at->format('Y-m-d H:i') : null,
            'space_num' => $this->parkSpace->number ?? null,
            'car_num' => $this->userCar->car_number ?? null,
            'mobile' => $this->user->mobile,
            'deduct_amount' => $this->deduct_amount,    // 预约实际扣款
            'divide_amount' => $this->divide->park_fee ?? 0,    // 车场分成
            'car_in_time' => $this->userOrder ? ($this->userOrder->carStop ? $this->userOrder->carStop->car_in_time->format('Y-m-d H:i'): null) : null,
            'refund_amount' => $this->refund_total_amount,  //  退款费用
            'no' => $this->order_no,
            'transaction_id' => $this->orders->map->transaction_id,
            'type' => $publisher_type[$this->carRent->rent_type_id ?? 1],
            'apt_time_range' => $this->apt_start_time . '-' .  $this->car_in_time,
            'apt_time' => $this->apt_time,
            'status' => $status
        ];
    }
}
