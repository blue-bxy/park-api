<?php

namespace App\Exceptions;

use Exception;

class CouponNotFundException extends ApiResponseException
{
    protected $message = "优惠券已失效或不存在";

    protected $code = 40010;
}
