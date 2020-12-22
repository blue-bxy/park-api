<?php

namespace App\Exceptions;

use Throwable;

class UserMobileNotBindException extends ApiResponseException
{
    public function __construct($data = null, $message = "请绑定手机号", $code = 60001, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->data = $data;
    }
}
