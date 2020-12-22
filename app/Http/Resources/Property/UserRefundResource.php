<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRefundResource extends JsonResource
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
            'order_id' => $this->order_id,
            'refund_no' => $this->refund_no,
            'refunded_amount' => $this->refunded_amount,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
