<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class ApiResponseException extends Exception
{
    protected $message;

    protected $code = 0;

    protected $data;
    /**
     * render
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ReflectionException
     */
    public function render(Request $request)
    {
        $http_response = new \ReflectionClass(FoundationResponse::class);

        $http_status = array_values($http_response->getConstants());

        $status = in_array($this->getCode(), $http_status) ? $this->getCode() : FoundationResponse::HTTP_OK;

        $data = [
            'message'    => $this->getMessage() ?? $this->message,
            'code'       => $this->code,
            'timestamps' => time()
        ];

        if ($this->data) {
            $data['data'] = $this->data;
        }

        return response()->json($data, $status);
    }

    public function setData($data)
    {
        $this->data = $data;
    }
}
