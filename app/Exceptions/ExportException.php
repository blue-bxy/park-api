<?php

namespace App\Exceptions;

class ExportException extends ApiResponseException
{
    protected $code = 40009;

    protected $message = '导出失败';
}
