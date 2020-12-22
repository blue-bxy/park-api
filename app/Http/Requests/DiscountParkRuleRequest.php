<?php

namespace App\Http\Requests;


class DiscountParkRuleRequest extends BaseRequest
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
                'is_active' => 'required|integer'
            ];
        }
        return [
            'title' => 'required|min:1',
            'province_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'park_property' => 'required|integer',
            'desc' => 'nullable'
        ];
    }
}
