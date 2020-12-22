<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkVirtualSpaceResource extends JsonResource
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
            'number' => $this->number,
            'is_stop' => $this->is_stop,
            'park_space_id' => $this->park_space_id,
            'park_area_id' => $this->park_area_id,
            'park_id' => $this->park_id
        ];
    }
}
