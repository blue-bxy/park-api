<?php

namespace App\Http\Requests\Property;

use App\Http\Requests\BaseRequest;

class StoreDiscountRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'=>'required|string',
            'coupon_rule_type'=>'required|string',
            'coupon_rule_value'=>'required|string',
            'max_receive_num'=>'required|numeric|min:0',
            'quota'=>'required|numeric|min:0',
            'start_time'=>'nullable',
            'end_time'=>'nullable',
            'no'=>'required|string ',
        ];
    }
}
