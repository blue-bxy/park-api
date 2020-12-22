<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBalanceRecordResouce extends JsonResource
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
            'record_id' => $this->getKey(),
            'order_no' => $this->order_no,
            'amount' => $this->amount,
            'trade_type' => $this->trade_type,
            'body' => $this->body,
            'fee' => $this->fee,
            'gateway' => $this->gateway,
            'gateway_rename' => $this->gateway_rename,
            'type' => $this->type,
            'status' => $this->status,
            'status_rename' => $this->status_rename,
            'add_time' => $this->created_at->format("Y-m-d H:i"),
        ];
    }
}
