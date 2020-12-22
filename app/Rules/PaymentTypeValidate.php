<?php

namespace App\Rules;

use App\Packages\Payments\Config;
use App\Packages\Payments\PaymentType;
use Illuminate\Contracts\Validation\Rule;

class PaymentTypeValidate implements Rule
{
    protected $gateway;

    /**
     * PaymentTypeValidate constructor.
     * @param $gateway
     */
    public function __construct($gateway)
    {
        $this->gateway = $gateway;
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
        return !($this->gateway == Config::DEFAULT_CHANNEL && $value == PaymentType::PAYMENT_TYPE_CHARGE);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '不支持的支付方式';
    }
}
