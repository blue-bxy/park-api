<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ParkGateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'programme' => 'integer',
            'brand' => 'min:1',
            'version' => 'min:1',
            'mode' => 'integer',
            'payment_mode' => 'integer',
            'is_active' => 'integer'
        ];
        if ($this->routeIs('*.store')) {
            $rules = array_map(function ($rule) {
                return 'required|'.$rule;
            }, $rules);
            $rules['park_id'] = 'required|integer';
        }
        return $rules;
    }
}
