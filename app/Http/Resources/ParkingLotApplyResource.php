<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkingLotApplyResource extends JsonResource
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
            'id' => $this->getKey(),
            'village_name' => $this->village_name,
            'village_detail' => $this->address,
            'nickname' => $this->nickname,
            'telephone' => $this->telephone,
            'village_telephone' => $this->village_telephone,
            'status' => $this->status,
            'status_rename' => $this->status_rename,
            'apply_time' => $this->created_at->timestamp
        ];
    }
}
