<?php

namespace App\Exceptions;


class InvalidArgumentException extends ApiResponseException
{
    protected $message = "参数未通过检验";
    protected $code = "40022";
}
