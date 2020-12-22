<?php

namespace App\Exceptions;

class ParkSpaceNotFoundException extends ApiResponseException
{
    protected $message = "车位不存在或未开放";

    protected $code = 30101;
}
