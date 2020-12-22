<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ParkCameraRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:50',
            'ip' => 'required|max:48',
            'protocol' => 'required|max:50',
            'gateway' => 'required|max:48',
            'park_id' => 'required|integer',
            'park_area_id' => 'required|integer'
        ];
    }
}
