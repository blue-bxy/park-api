<?php

namespace App\Exceptions;

class BadMethodException extends ApiResponseException
{
    protected $message = "方法不存在";

    protected $code = 40004;
}
