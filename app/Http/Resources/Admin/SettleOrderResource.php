<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class SettleOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($this->status) {
            case 'pending':
                $status = '待支付';
                break;

            case 'paid':
                $status = '已支付';
                break;

            case 'cancelled':
                $status = '已取消';
                break;

            case 'refunded':
                $status = '已退款';
                break;

            case 'failed':
                $status = '失败';
                break;

            default:
                $status = '已完成';
        }
        return [
//            'id'=>$this->id,
//            'order_no'=>$this->no,
//            'created_at'=>$this->created_at->format('Y-m-d'),
//            'car_num'=>$this->carApt->userCar->car_number??null,
//            'stops' => $this->whenLoaded('order', function () {
//                return [
//                    'car_in_time' => $this->order?($this->order->carStop?($this->order->carStop->car_in_time?$this->order->carStop->car_in_time->format('Y-m-d H:i'):null):null):null,
//                    'car_out_time' => $this->order?($this->order->carStop?($this->order->carStop->car_out_time?$this->order->carStop->car_out_time->format('Y-m-d H:i'):null):null):null,
//                    'stop_time' =>  $this->order->carStop->stop_time ?? null,
//                    'stop_price' =>  $this->order->carStop->stop_price ?? null,
//                ];
//            }),
//            'apt_start_time'=>$this->created_at->format('Y-m-d H:m'),
//            'apt_time'=>$this->subscribe_time,
//            'apt_price'=>$this->amount,
//            'park_name'=>$this->carApt->parks->project_name??null,
//            'status'=>$this->status,
//            'status_rename'=>$this->status_rename,

            'id'=>$this->id,
            'order_no'=>$this->order_no,
            'created_at'=>$this->created_at->format('Y-m-d'),
            'car_num'=>$this->car->car_number ?? null,
            'stops' => $this->whenLoaded('carStop', function () {
                return [
                    'car_in_time' => $this->carStop?($this->carStop->car_in_time?$this->carStop->car_in_time->format('Y-m-d H:i'):null):null,
                    'car_out_time' => $this->carStop?($this->carStop->car_out_time?$this->carStop->car_out_time->format('Y-m-d H:i'):null):null,
                    'stop_time' =>  $this->carStop->stop_time ?? null,
                    'stop_price' =>  $this->amount ?? null,
                ];
            }),
            'apt_start_time'=> $this->carApts ? $this->carApts->apt_start_time->format('Y-m-d H:i') : null,
            'apt_time'=> $this->carApts->apt_time ?? null,
            'apt_price'=>$this->total_amount,
            'deduct_amount'=> $this->carApts->deduct_amount ?? null,
            'park_name'=>$this->parks->project_name ?? null,
            'status'=>$this->status,
            'status_rename'=> $status,
        ];
    }
}
