<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCouponResource extends JsonResource
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
            'title' => $this->title,
            'used_amount' => $this->amount,
            'use_min_amount' => $this->use_min_amount,
            'start_time' => $this->start_time ? $this->start_time->format('Y-m-d H:i') : null,
            'end_time' => $this->end_time ? $this->end_time->format('Y-m-d H:i') : null,
            'expired_at' => $this->expiration_time->format('Y-m-d H:i'),
            'has_used' => $this->has_used,
            'has_expired' => $this->has_expired,
            'status' => $this->status,
            'status_rename' => $this->status_rename,
            'description' => $this->description,
            'limit' => $this->limit,
        ];
    }
}
