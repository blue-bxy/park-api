<?php

namespace App\Http\Requests;


class DiscountRuleRequest extends BaseRequest
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
            'title' => 'required|string',
            'amount' => 'required|integer',
            'use_scene' => 'required|integer',
            'type' => 'required|integer',
            'value' => 'nullable',
            'desc' => 'nullable',
        ];
    }
}
