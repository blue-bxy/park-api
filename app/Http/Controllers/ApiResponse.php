<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

/**
 * Trait ApiResponse
 * @package App\Http\Controllers
 */
trait ApiResponse
{

    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @var array
     */
    protected $content = [];

    /**
     * @var int
     */
    protected $code = 0;

    /**
     * @var
     */
    protected $message;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param $message
     * @return $this
     */
    protected function message($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param null $data
     * @return $this
     */
    protected function content($data = null)
    {
        $this->content = [
            'message'    => $this->getMessage(),
            'code'       => $this->getCode(),
            'timestamps' => time()
        ];

        if (!is_null($data)) {
            $this->setContentData($data);
        }
        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    protected function setContentData($data)
    {
        Arr::set($this->content, 'data', $data);

        return $this;
    }

    /**
     * @param $code
     * @return $this
     */
    private function code($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    private function getCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    protected function getContent()
    {
        return $this->content;
    }

    /**
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function response($data = null)
    {
        $this->content($data);

        if (func_num_args() === 2) {
            $this->setStatusCode(func_get_arg(1));
        }

        return response()->json($this->getContent(), $this->getStatusCode());
    }

    /**
     * @param $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseData($data, $message = 'success', $code = 0)
    {
        return $this->message($message)->code($code)->response($data);
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($message = 'success', $code = 0)
    {
        return $this->message($message)->code($code)->response();
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound($message = 'Not Found', $code = 0)
    {
        return $this->message($message)->code($code)->response();
    }

    /**
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseFailed($message = 'failed', $code = 0)
    {
        return $this->message($message)->code($code)->response();
    }

    /**
     * @param string $message
     * @param int $code
     * @param array|string|int|null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseMessage($message = 'success', $code = 0, $data = null)
    {
        return $this->message($message)->code($code)->response($data);
    }
}
