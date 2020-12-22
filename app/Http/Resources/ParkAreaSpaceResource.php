<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkAreaSpaceResource extends JsonResource
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
            'area_code' => $this->code,
            'name' => $this->name,
            'floor' => $this->floor,
            'mode' => $this->manufacturing_mode, // 运营模式
            'height_limit' => $this->garage_height_limit, // 限高
            'carport_count' => $this->parking_places_count,
            'fixed_count' => $this->fixed_parking_places_count, // 固定车位数
            'temp_count' => $this->temp_parking_places_count, // 临时车位数
            'can_publish' => $this->can_publish_spaces,
            'items' => $this->whenLoaded('spaces', function () {
                return ParkSpaceResource::collection($this->spaces);
            })
        ];
    }
}
