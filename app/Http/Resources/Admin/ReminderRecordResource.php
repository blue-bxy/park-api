<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ReminderRecordResource extends JsonResource
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

        if($this->admin_id){
            $admin = $this->admin->name;
        }else{
            $admin = "系统";
        }

        return [
            'id' => $this->id,
            'park' => $this->reminder->park->project_name ?? null,
            'order_no' => $this->reminder->order_no ?? null,
            'phone' => $this->reminder->phone ?? null,
            'car_num' => $this->reminder->car_num ?? null,
            'car_in_time' => $this->reminder->car_in_time ?? null,
            'car_out_time' => $this->reminder->car_out_time ?? null,
            'stop_time' => $this->reminder->stop_time ?? null,
            'amount' => $this->reminder->amount ?? null,
            'deduct_amount' => $this->reminder->deduct_amount ?? null,
            'days_overdue' => $this->reminder->days_overdue ?? null,
            'state' => $state,
            'feedback' => $this->feedback,
            'reminder_time' => $this->created_at->format('Y-m-d H:i'),
            'admin' => $admin
        ];
    }
}
