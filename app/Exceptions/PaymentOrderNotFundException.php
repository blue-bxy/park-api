<?php

namespace App\Exceptions;

use Exception;

class PaymentOrderNotFundException extends ApiResponseException
{
    protected $message = "订单已失效或不存在";

    protected $code = "30002";
}
