<?php

namespace App\Http\Requests;

class DiscountUserRuleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'is_activity_active' => 'required|integer',
            'active_days' => 'integer',
            'activity_setting_days' => 'integer',
            'is_regression_active' => 'required|integer',
            'regression_days' => 'integer',
            'is_new_user' => 'required|integer',
            'is_active' => 'required|integer',
            'desc' => 'nullable',
        ];
    }
}
