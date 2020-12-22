<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class  ParkAreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [];
        if ($request->routeIs('admin.parks.sidebar') ||
            $request->routeIs('admin.park_area.simplified_list')) {
            $data = [
                'id' => $this->id,
                'name' => $this->name,
                'code' => $this->code
            ];
        } else {
            $data = [
                'id' => $this->id,
                'name' => $this->name,
                'code' => $this->code,
                'attribute' => $this->attribute,
                'status' => $this->status,
                'has_default' => $this->has_default,
                'car_model' => $this->car_model,
                'parking_places_count' => $this->parking_places_count,
                'temp_parking_places_count' => $this->temp_parking_places_count,
                'fixed_parking_places_count' => $this->fixed_parking_places_count,
                'charging_pile_parking_places_count' => $this->charging_pile_parking_places_count,
                'garage_height_limit' => $this->garage_height_limit,
                'can_publish_spaces' => $this->can_publish_spaces,
                'manufacturing_mode' => $this->manufacturing_mode
            ];
        }
        return $data;
    }
}
