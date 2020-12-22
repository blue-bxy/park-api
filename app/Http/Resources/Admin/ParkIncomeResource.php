<?php

namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class ParkIncomeResource extends JsonResource
{
    public function toArray($request)
    {
        switch($this->payment_gateway){
            case 'wx_app':
                $this->payment_gateway = '微信';
                break;
            case 'ali_app':
                $this->payment_gateway = '支付宝';
                break;
            default:
                $this->payment_gateway = '余额';
        }

        return [
            'id' => $this->id,
            'park_name' => $this->parks->project_name ?? null,
            'order_no' => $this->order_no,
            'car_stop_id' => $this->carStop->id ?? null,
            'car_num' => $this->car ? $this->car->car_number : $this->car_num,
            'create_time' => $this->carApts ? $this->carApts->apt_start_time->format('Y-m-d H:i') : null,
            'apt_time' => $this->carApts->apt_time ?? null,
            'subscribe_amount' => $this->subscribe_amount,  // 预约总金额
            'refund_amount' => $this->refund_amount,        // 预约退款
            'discount_amount' => $this->discount_amount,    // 预约优免金额
            'payment_gateway' => $this->payment_gateway,    // 预约支付方式
            'car_in_time' =>  $this->carStop ? ($this->carStop->car_in_time ? $this->carStop->car_in_time->format('Y-m-d H:i') : null) : null,
            'car_out_time' =>  $this->carStop ? ($this->carStop->car_out_time ? $this->carStop->car_out_time->format('Y-m-d H:i') : null) : null,
            'stop_time' => $this->carStop->stop_time ?? null,
            'amount' =>$this->amount,                   // 理论的停车费
            'parking_fee' => $this->parking_fee,        // app显示需支付的停车金额
            'optimal_free' => $this->carStop->discount_amount ?? null,  // 停车优免金额
            'stop_order_status' => $this->carStop->order->status ?? null,   // 停车支付状态
            'stop_payment_gateway' => $this->carStop->order->payment_gateway ?? null,    // 停车的支付方式
            'explain' => $this->explain,    // 操作说明
        ];
    }
}
