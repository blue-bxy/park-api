<?php

namespace App\Http\Requests\App;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserCarRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'car_number' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'car_number.required' => '车牌号不能为空',
        ];
    }
}
