<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ParkAreaRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->routeIs('*.update')) {
            return [
                'name' => 'max:50',
                'attribute' => 'integer',
                'status' => 'integer',
                'car_model' => 'integer',
                'parking_places_count' => 'integer',
                'temp_parking_places_count' => 'integer',
                'fixed_parking_places_count' => 'integer',
                'charging_pile_parking_places_count' => 'integer',
                'garage_height_limit' => 'numeric',
                'can_publish_spaces' => 'integer',
                'manufacturing_mode' => 'integer'
            ];
        }
        return [
            'name' => 'required|max:50',
            'code' => 'required',
            'attribute' => 'required|integer',
            'status' => 'required|integer',
            'car_model' => 'required|integer',
            'parking_places_count' => 'required|integer',
            'temp_parking_places_count' => 'required|integer',
            'fixed_parking_places_count' => 'required|integer',
            'charging_pile_parking_places_count' => 'required|integer',
            'garage_height_limit' => 'required|numeric',
            'manufacturing_mode' => 'integer',
            'park_id' => 'required|integer'
        ];
    }
}
