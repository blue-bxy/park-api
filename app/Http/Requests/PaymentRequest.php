<?php

namespace App\Http\Requests;


use App\Packages\Payments\Config;
use App\Rules\PaymentGatewayValidate;
use App\Rules\PaymentTypeValidate;

class PaymentRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'gateway' => [
                'required',
                new PaymentGatewayValidate()
            ],
            'type' => [
                'required',
                new PaymentTypeValidate($this->input('gateway'))
            ],
            // 'amount' => 'required|integer|min:100',
            'order_no' => 'required',
            // 钱包支付 需要输入密码
            // 'password' => 'required_if:gateway,'. Config::DEFAULT_CHANNEL
        ];
    }

    public function messages()
    {
        return [
            'gateway.required' => '请选择支付方式',
            'order_no.required' => '订单号不能为空',
            'password.required_if' => '钱包支付需要确认支付密码',
            'type.required' => '业务类型未知',

        ];
    }
}
