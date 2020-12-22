<?php

namespace App\Rules;

use App\Packages\Payments\Config;
use App\Packages\Payments\Payment;
use Illuminate\Contracts\Validation\Rule;

class PaymentGatewayValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array($value, Payment::getGateways());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '暂不支持其他支付方式';
    }
}
