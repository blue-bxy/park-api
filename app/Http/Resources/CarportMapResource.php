<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CarportMapResource extends JsonResource
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
            'map_key' => config('lingtong.indoor_map_key'),
            'find_car_url' => $this->getFindCarUrl(),
            'find_parking_url' => $this->getFindParkingUrl(),
            $this->mergeWhen(!empty($this->getMapParams()), function () {
                return $this->getMapParams();
            })
        ];
    }
}
