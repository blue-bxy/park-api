<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumberValidate;

class DiscountRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required',
            'coupon_rule_id' => 'required|integer',
            'coupon_park_rule_id' => 'integer',
            'max_receive_num' => 'required|integer',
            'valid_start_time' => 'required',
            'valid_end_time' => 'required',
            'park_id' => 'integer',
            'distribution_method' => 'required|integer'
        ];
        if ($this->input('distribution_method') == 5) { //快速发放
//        if ($this->input('method') == 'fast') {
            return array_merge($rules, [
                'assign_user' => ['sometimes', 'required', 'array'],
                'assign_user.*' => ['required', new PhoneNumberValidate()]
            ]);
        }
        return array_merge($rules, [
            'quota' => 'required|integer',
            'coupon_user_rule_id' => 'required|integer',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);
    }
}
