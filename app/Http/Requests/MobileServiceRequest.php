<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumberValidate;

class MobileServiceRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'mobile'   => ['required', 'string', new PhoneNumberValidate()],
            'sms_key'  => 'required|string',
            'sms_code' => 'required|size:4'
        ];
    }

    public function messages()
    {
        return [
            'mobile.required' => '手机号必填',
            'sms_code.required' => '验证码不能为空',
            'sms_code.size' => '无效的验证码',
            'sms_key.required' => ':attribute不能为空',
        ];
    }
}
