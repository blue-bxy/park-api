<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkingSpaceResource extends JsonResource
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
            'parking_id' => $this->id,
            'park_id' => $this->park_id,
            'park_name' => $this->park->project_name,
            'status' => $this->status,
            'status_rename' => $this->status_rename,
            'number' => $this->number,
            'address' => $this->park->address(),
            'has_opened' => $this->hasOpen(),
            'has_allowed' => $this->hasAllowed(),
            'rent' => $this->when($this->rent, function () {
                return [
                    'rent_id' => $this->rent->id,
                    'price' => $this->rent->rent_price,
                    'start' => $this->rent->start,
                    'stop' => $this->rent->stop,
                    'pics' => $this->rent->covers
                ];
            })
        ];
    }
}
