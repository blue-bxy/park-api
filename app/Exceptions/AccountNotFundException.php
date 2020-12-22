<?php

namespace App\Exceptions;


class AccountNotFundException extends ApiResponseException
{
    protected $message = '第三方账号不存在，请前往授权';

    protected $code = 50003;
}
