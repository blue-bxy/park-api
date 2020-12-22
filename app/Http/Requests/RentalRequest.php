<?php

namespace App\Http\Requests;

class RentalRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'price' => 'required|numeric|min:1',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'pics' => 'sometimes|required|array',
            'pics.*' => 'required|image',
            'status' => 'required|boolean'
        ];

        if ($this->isMethod('post')) {
            $rules['parking_id'] = 'required';
        }

        return $rules;

    }
}
