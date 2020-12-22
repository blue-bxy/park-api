<?php

namespace App\Http\Resources\Admin;

use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkRateResource extends JsonResource
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
            'no' => $this->no,
            'name' => $this->name,
            'project_name' => $this->park->project_name,
            'is_workday' => $this->is_workday,
            'start_period' => $this->start_period,
            'end_period' => $this->end_period,
            'down_payments' => $this->down_payments,
            'down_payments_time' => $this->down_payments_time,
            'time_unit' => $this->time_unit,
            'payments_per_unit' => $this->payments_per_unit,
            'first_day_limit_payments' => $this->first_day_limit_payments,
            'is_active' => $this->is_active,
            'parking_spaces_count' => $this->parking_spaces_count,
            'publisher_type' => $this->getPublisherTypeAttribute($this->publisher_type),
            'publisher_id' => $this->publisher_id,
            'park_area_id' => $this->park_area_id,
            'park_area_name' => $this->park_area_id ? $this->area->name: null,
            'park_id' => $this->park_id,
            'type' => $this->type,
            'created_at' => $this->created_at
        ];
    }

    protected function getPublisherTypeAttribute($value) {
        if ($value === User::class) {
            $value = 'user';
        } elseif ($value === Property::class) {
            $value = 'property';
        } else {
            $value = 'admin';
        }
        return $value;
    }

}
