<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserConsumptionRecodeResource extends JsonResource
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
			'park_name' => $this->park_name,
            'created_at' => time_format($this->created_at, 'Y.n.d H:i'),
            'amount' => $this->amount,
			'payment_method' => $this->payment_channel,
//			'business_type' => $this->business_type
		];
    }
}
