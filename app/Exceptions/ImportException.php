<?php

namespace App\Exceptions;

class ImportException extends ApiResponseException
{
    protected $code = 40008;

    protected $message = '导入失败';

}
