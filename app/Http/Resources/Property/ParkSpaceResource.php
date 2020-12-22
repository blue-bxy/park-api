<?php

namespace App\Http\Resources\Property;

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
        if ($request->routeIs('property.park_cameras.pictures')) {
            $data = [
                'id' => $this->id,
                'number' => $this->number,
                'status' => $this->status,
                'pic' => $this->pic
            ];
            if ($this->status == ParkSpace::STATUS_PARKING) {
                $data['car_number'] = $this->car_num;
            }
            return $data;
        }
        $data = [
            'id' => $this->id,
            'number' => $this->number,
            'type' => $this->type,
            'category' => $this->category,
            'status' => $this->status,
            'park_area_id' => $this->park_area_id,
            'pic' => $this->pic,
            'has_lock' => empty($this->locks) ? 0 : 1
        ];
        if (in_array($this->status, array(ParkSpace::STATUS_RESERVING, ParkSpace::STATUS_RESERVED))) {
            if (!empty($apt = $this->carApt)) {
                $data['car_number'] = $apt->userCar->car_number ?? null;
                $data['start_time'] = $apt->apt_start_time;
                $data['end_time'] = $apt->apt_end_time;
            }
        } elseif ($this->status == ParkSpace::STATUS_PARKING) {
            $data['car_number'] = $this->car_num;
            if (!empty($stop = $this->carStop)) {
                $data['start_time'] = $stop->car_in_time;
            }
        }
        return $data;
    }
}
