<?php


namespace App\Services\Templates\Sms;


use Overtrue\EasySms\Contracts\GatewayInterface;
use Overtrue\EasySms\Message;

class LoginMessage extends Message
{
    protected $code;

    protected $minute;

    public function getContent(GatewayInterface $gateway = null)
    {
        return sprintf('验证码%s，请在%s分钟内输入验证码。如非本人操作，请忽略本短信。', $this->code, $this->minute);
    }

    public function getTemplate(GatewayInterface $gateway = null)
    {
        return 'SMS_193516325';
    }

    // 模板参数
    public function getData(GatewayInterface $gateway = null)
    {
        return [
            'code' => $this->code,
        ];
    }
}
