<?php

namespace App\Http\Resources\Property;

use App\Models\Parks\ParkRate;
use App\Models\Property;
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
        if ($request->routeIs('property.park_rates.index')) {
            $data = [
                'id' => $this->id,
//                'price' => number_format($this->payments_per_unit * 60 / $this->time_unit, 2),
                'price' => intval($this->payments_per_unit * 60 / $this->time_unit),
                'parking_spaces_count' => $this->parking_spaces_count,
                'start_period' => $this->start_period,
                'end_period' => $this->end_period,
                'property_name' => null,
                'publisher_type' => $this->getPublisherTypeAttribute($this->publisher_type),
                'is_active' => $this->is_active,
                'type' => $this->type,
                'created_at' => $this->created_at
            ];
            if ($data['publisher_type'] == 'property') {
                $data['property_name'] = $this->publisher->name;
            }
        } elseif ($request->routeIs('property.park_rates.show') ||
            $request->routeIs('property.park_spaces.rate')) {
            $data = [
                'id' => $this->id,
                'name' => $this->name,
                'is_workday' => $this->is_workday,
                'start_period' => $this->start_period,
                'end_period' => $this->end_period,
                'first_day_limit_payments' => $this->first_day_limit_payments,
                'down_payments' => $this->down_payments,
                'down_payments_time' => $this->down_payments_time,
                'time_unit' => $this->time_unit,
                'payments_per_unit' => $this->payments_per_unit,
                'type' => $this->type,
                'parking_spaces_count' => $this->parking_spaces_count,
                'is_active' => $this->is_active
            ];
            if ($this->type == ParkRate::TYPE_SPACE && $request->routeIs('*.show')) {
                $data['park_space_number'] = $this->spaces[0]->number;
            }
        }
        return $data;
    }

    protected function getPublisherTypeAttribute($value) {
        if ($value === Property::class) {
            $value = 'property';
        } else {
            $value = 'admin';
        }
        return $value;
    }
}
