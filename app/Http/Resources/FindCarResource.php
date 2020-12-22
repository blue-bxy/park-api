<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FindCarResource extends JsonResource
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
            'order_id' => $this->getKey(),
            'park_id' => $this->space->park_id,
            'park_name' => $this->parks->park_name,
            'park_address' => $this->parks->address(),
            'longitude' => $this->parks->longitude,
            'latitude' => $this->parks->latitude,
            'car_number' => $this->car->car_number,
            'park_space' => [
                'park_space_id' => $this->space->id,
                'floor' => $this->space->floor,
                'park_space_number' => $this->space->number,
                'map_space_id' => $this->space->map_unique_id,
            ],
            'maps' => $this->when($this->parks, function () {
                return new CarportMapResource($this->parks);
            })
        ];
    }
}
