<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserOrderAppointmentResource extends JsonResource
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
			'user_id' => $this->id,
			'parking_place' => $this->parking_place,
			'parking_duration' => $this->parking_duration,
			'car_num' => $this->car_num,
			'unit_price' => $this->unit_price,
		];
    }
}
