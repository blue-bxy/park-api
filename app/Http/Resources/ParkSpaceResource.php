<?php

namespace App\Http\Resources;

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
            'space_id' => $this->id,
            // 'space_area_code' => $this->area->code,
            'has_lock' => (bool) $this->locks_count,
            'space_number' => $this->number,
            'space_type' => $this->type,
            'map_space_id' => $this->map_unique_id,
            // 'floor' => $this->area->floor,
            'floor' => $this->floor,
            'space_category' => $this->category,
            'is_reserved_type' => $this->is_reserved_type,
            'status' => $this->status,
            // $this->mergeWhen($this->carApt, function () {
            //     return [
            //         'subscribe_start' => $this->carApt->apt_start_time->timestamp ?? null,
            //         'subscribe_end' => $this->carApt->apt_end_time->timestamp ?? null,
            //     ];
            // })
            // 出租时间段
            'rental_period' => $this->whenLoaded('rental', function () {
                return $this->rental->getRentalTime();
            })
        ];
    }
}
