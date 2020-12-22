<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {

            if (method_exists($exception, 'render')) {
                return $exception->render($request);
            }

            if ($exception instanceof AuthenticationException) {
                throw new \App\Exceptions\AuthenticationException();
            } elseif ($exception instanceof ThrottleRequestsException) {
                throw new ApiResponseException("操作太频繁", 40029);
            } elseif ($exception instanceof ValidationException) {
                throw new InvalidArgumentException($exception->validator->errors()->first(), 40022);
            } elseif ($exception instanceof AuthorizationException) {
                throw new InvalidArgumentException($exception->getMessage(), 40005);
            } elseif ($exception instanceof NotFoundHttpException
                || $exception instanceof MethodNotAllowedHttpException) {
                throw new ApiResponseException('接口不存在', 40006);
            } elseif ($exception instanceof ModelNotFoundException) {
                throw new ApiResponseException('数据不存在', 40007);
            } elseif ($exception instanceof \Exception) {
                return response()->json([
                    'message'    => '接口参数待确认',
                    'code'       => 40004,
                    'timestamps' => time()
                ]);
            }
        }
        return parent::render($request, $exception);
    }
}
