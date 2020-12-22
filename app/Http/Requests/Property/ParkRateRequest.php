<?php

namespace App\Http\Requests\Property;

use App\Http\Requests\BaseRequest;
use App\Models\Parks\ParkRate;

class ParkRateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->input('type') == ParkRate::TYPE_SPACE) {
            return [
                'is_workday' => 'required|integer',
                'start_period' => 'required|integer',
                'end_period' => 'required|integer',
                'down_payments' => 'required|integer',
                'down_payments_time' => 'required|integer',
                'time_unit' => 'required|integer',
                'payments_per_unit' => 'required|integer',
                'first_day_limit_payments' => 'required|integer',
                'is_active' => 'required|integer',
                'type' => 'required|integer',
                'park_space_id' => 'required|integer'
            ];
        }
        return [
            'name' => 'required|min:1',
            'is_workday' => 'required|integer',
            'start_period' => 'required|integer',
            'end_period' => 'required|integer',
            'down_payments' => 'required|integer',
            'down_payments_time' => 'required|integer',
            'time_unit' => 'required|integer',
            'payments_per_unit' => 'required|integer',
            'first_day_limit_payments' => 'required|integer',
            'is_active' => 'required|integer',
            'parking_spaces_count' => 'required|integer',
            'type' => 'required|integer'
        ];
    }
}
