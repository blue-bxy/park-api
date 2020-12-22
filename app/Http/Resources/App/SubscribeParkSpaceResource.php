<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscribeParkSpaceResource extends JsonResource
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
            'park_id' => $this->id,
            'park_name' => $this->park_name,
            'height_permitted' => $this->park_height_permitted, //限高
            'distance' => round($this->distance, 2), // 距离
            'fee' => $this->fee, // 费用
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'reserved_spaces_count' => $this->reserved_spaces_count, // 余位
            'charging_pile_count' => $this->charging_pile_count, // 充电桩
            'car_category' => '小型车', // 车型
            'has_favorite' => (bool) $this->has_favorite ?? false,
            'free_time' => $this->stall->free_time, // 免费时长
        ];
    }
}
