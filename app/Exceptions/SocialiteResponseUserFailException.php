<?php

namespace App\Exceptions;


class SocialiteResponseUserFailException extends ApiResponseException
{
    protected $message = "用户授权信息获取失败，请重新授权";

    protected $code = "50002";
}
