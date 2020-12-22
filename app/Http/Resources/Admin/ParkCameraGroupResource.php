<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkCameraGroupResource extends JsonResource
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
            'name' => $this->name,
            'unique_id' => $this->unique_id,
            'total_count' => $this->total_count,
            'available_count' => $this->available_count,
            'cameras_count' => $this->cameras->count(),
            'is_active' => $this->is_active,
            'park_id' => $this->park_id,
            'park_area_id' => $this->park_area_id,
            'cameras' => ParkDeviceResource::collection($this->cameras)
        ];
    }
}
