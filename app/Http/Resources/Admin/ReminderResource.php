<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        switch ($this->state){
            case '1':
                $state = '未催收';
                break;
            case '2':
                $state = '推送通知';
                break;
            case '3':
                $state = '短信';
                break;
            case '4':
                $state = '人工催收';
                break;
        }

        if($this->pay_status == 'pending'){
            $status = '未支付';
        }

        if($this->pay_status == 'paid'){
            $status = '已支付';
        }

        return [
            'id' => $this->id,
            'park_name' => $this->park->project_name ?? null,
            'order_no' => $this->order_no,
            'phone' => $this->phone,
            'car_num' => $this->car_num,
            'car_in_time' => $this->car_in_time,
            'car_out_time' => $this->car_out_time,
            'stop_time' => $this->stop_time,
            'amount' => $this->amount,
            'deduct_amount' => $this->deduct_amount,
            'days_overdue' => $this->days_overdue,
            'state' => $state,
            'pay_status' => $status
        ];
    }
}
