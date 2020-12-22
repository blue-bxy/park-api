<?php

namespace App\Http\Resources\Admin;

use App\Models\Dmanger\CarAptOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class SettleRefundResource extends JsonResource
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
            'id'=>$this->id,
            'refund_no'=>$this->refund_no,
            'refund_business'=>'支付退款',
            'actual_price'=>$this->amount,
            'refunded_amount'=>$this->refunded_amount,
            'refund_status'=>$this->refunded_at?'已退款':'未退款',
            'order_no'=>$this->no()??null,
            'refund_channels'=>$this->refund_channels,
            'park_name'=>$this->park()??null,
            'type'=>$this->type,
            'created_at'=>$this->created_at->format('Y-m-d'),
            'reason'=>$this->reason,
            'operator'=>$this->operator
        ];
    }

    protected function no()
    {
        return $this->order instanceof CarAptOrder ? $this->order->no:null;
    }
    protected function park()
    {
        return $this->order instanceof CarAptOrder ? $this->order->carApt->parks->project_name:null;
    }
}
