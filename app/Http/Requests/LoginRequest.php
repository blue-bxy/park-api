<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ];
        // 非开发环境需要验证码
        if (! app()->isLocal()) {
            $rules = array_merge($rules, [
                'key'      => 'required',
                'captcha'  => 'required|captcha_api:' . $this->input('key'),
            ]);
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'email.required'      => '邮箱不能为空',
            'email.string'        => '邮箱数据格式错误',
            'email.email'         => '邮箱格式错误',
            'key.required'        => '验证码key不能为空',
            'password.required'   => '密码不能为空',
            'password.string'     => '密码只能是字符串',
            'captcha.required'    => '验证码不能为空',
            'captcha.captcha_api' => '验证码错误',
        ];
    }
}
