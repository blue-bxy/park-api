<?php


namespace App\Services;


use App\Exceptions\InvalidArgumentException;
use Illuminate\Http\Request;

class ValidateService
{
    /**
     * checkCode
     *
     * @param Request $request
     * @return bool
     * @throws InvalidArgumentException
     */
    public function checkCode(Request $request)
    {
        $sms_key = $request->input('sms_key');
        $sms_code = $request->input('sms_code');

        return $this->validateCode($sms_key, $sms_code);
    }

    /**
     * validateCode
     *
     * @param $key
     * @param $code
     * @return bool
     * @throws InvalidArgumentException
     */
    protected function validateCode($key, $code)
    {
        $verifyData = \Cache::get($key);

        if (empty($verifyData)) {
            throw new InvalidArgumentException('验证码已失效', 30022);
        }

        if (!hash_equals((string) $verifyData['code'], $code)) {
            throw new InvalidArgumentException('验证码错误，请重新输入', 30023);
        }

        \Cache::forget($key);

        return true;
    }
}
