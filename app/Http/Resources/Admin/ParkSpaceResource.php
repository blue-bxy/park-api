<?php

namespace App\Http\Resources\Admin;

use App\Models\Parks\ParkSpace;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkSpaceResource extends JsonResource
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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'type' => $this->type,
            'category' => $this->category,
            'status' => $this->status,
            'is_active' => $this->status == ParkSpace::STATUS_DISABLED ? 0 : 1,
            'remark' => $this->remark
        ];
    }
}
