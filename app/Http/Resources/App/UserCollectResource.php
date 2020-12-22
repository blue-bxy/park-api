<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCollectResource extends JsonResource
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
			'park_id' => $this->parks->id,
			'park_name' => $this->parks->park_name,
			'address' => $this->parks->address(),
            // 'charging_standard' => $this->parks->parkRate->booking_rate,
            'charging_standard' => $rate = $this->parks->stall->fee_string,
            'score' => $this->parks->score,
		];
    }
}
