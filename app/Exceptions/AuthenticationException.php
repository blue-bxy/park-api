<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class AuthenticationException extends ApiResponseException
{
    protected $code = 40001;

    protected $message = 'Unauthenticated.';
}
