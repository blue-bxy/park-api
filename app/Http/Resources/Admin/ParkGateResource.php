<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkGateResource extends JsonResource
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
            'park_id' => $this->park_id,
            'project_name' => $this->park->project_name ?? null,
            'programme' => $this->programme,
            'brand' => $this->brand,
            'version' => $this->version,
            'mode' => $this->mode,
            'payment_mode' => $this->payment_mode,
            'is_active' => $this->is_active
        ];
    }
}
