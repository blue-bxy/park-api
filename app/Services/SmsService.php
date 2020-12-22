<?php


namespace App\Services;


use App\Jobs\SendMessageResult;
use App\Services\Templates\Sms\LoginMessage;
use Overtrue\EasySms\EasySms;

class SmsService
{
    /** @var string  */
    protected $type;

    /** @var string|int */
    protected $phone;

    /** @var string|int */
    protected $code;

    /** @var int */
    protected $minute = 10;

    public function send($phone)
    {
        try {
            $results = $this->service()->send($phone, $this->getMessage());

            dispatch(new SendMessageResult($phone, $this->getCode(), $this->getType(), json_encode($results)));
        } catch (\Exception $exception) {
            logger($exception);

            throw new \Exception('短信发送失败 请重试');
        }

        foreach ($results as $gateway => $result) {
            if ($result['status'] == 'failure') {
                throw new \Exception($result['exception']->getMessage());
            }
        }

        return $results;
    }

    public function getMessage()
    {
        $method = camel_case($this->type) . 'Message';

        if (!method_exists($this, $method)) {
            throw new \InvalidArgumentException();
        }

        return $this->$method();
    }

    /**
     * 登陆确认验证码
     *
     * @return LoginMessage
     */
    protected function loginMessage()
    {
        return new LoginMessage([
            'code' => $this->getCode(),
            'minute' => $this->getMinute()
        ]);
    }

    /**
     * sms
     *
     * @return EasySms
     */
    public function service()
    {
        return app('easysms');
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($value)
    {
        $this->code = $value;

        return $this;
    }

    public function getMinute()
    {
        return $this->minute;
    }

    public function setMinute(int $value)
    {
        $this->minute = $value;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(string $value)
    {
        $this->type = $value;

        return $this;
    }
}
