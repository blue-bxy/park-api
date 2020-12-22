<?php


namespace App\Packages\JPush;


class JPushMessage
{
    protected $client;
    protected $url;

    protected $cid;
    protected $platform;

    protected $audience;
    protected $tags = [];

    protected $notifications = [];
    protected $notificationAlert;

    protected $message = [];
    protected $options = [];

    protected $callback = [];

    public static function create()
    {
        return new static;
    }

    public function platform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function cid($cid)
    {
        $this->cid = $cid;

        return $this;
    }

    public function getCid()
    {
        return $this->cid;
    }

    public function message(string $content, array $options = [])
    {
        $this->message = array_replace_recursive([
            'msg_content' => $content
        ], $options);

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function allAudience()
    {
        $this->audience = 'all';

        return $this;
    }

    public function audience(string $key, array $value)
    {
        switch ($key) {
            case 'tag':
                $this->tags['tag'] = $value;
                break;
            case 'tag_and':
                $this->tags['tag_and'] = $value;
                break;
            case 'tag_not':
                $this->tags['tag_not'] = $value;
                break;
            case 'alias':
                $this->tags['alias'] = $value;
                break;
            case 'registration_id':
                $this->tags['registration_id'] = $value;
                break;
            case 'segment':
                $this->tags['segment'] = $value;
                break;
            case 'abtest':
                $this->tags['abtests'] = $value;
                break;
            default:
                break;
        }

        $this->audience = $this->tags;

        return $this;
    }

    public function getAudience()
    {
        return $this->audience;
    }

    public function options(array $options)
    {
        $this->options = array_replace_recursive($this->options, $options);

        return $this;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * notification
     *
     * @param string $platform
     * @param array|string $alert
     * @param array $options
     * @return $this
     */
    public function notification(string $platform, $alert, array $options = [])
    {
        $this->notifications[$platform] = array_replace_recursive([
            'alert' => $alert
        ], $options);

        return $this;
    }

    /**
     * iosNotification
     *
     * @param array|string $alert
     * @param array $options
     * @return $this
     */
    public function iosNotification($alert = '', array $options = [])
    {
        // 角标
        /*if (!isset($options['badge'])) {
            $options['badge'] = '+1';
        }

        // 声音
        if (!isset($options['sound'])) {
            $options['sound'] = '';
        }

        // 推送唤醒
        if (isset($options['content-available']) && !is_bool($options['content-available'])) {
            unset($options['content-available']);
        }

        // 通知拓展
        if (isset($options['mutable-content']) && !is_bool($options['mutable-content'])) {
            unset($options['mutable-content']);
        }

        // 附加字段
        if (isset($options['extras']) && is_array($options['extras']) && empty($options['extras'])) {
            unset($options['extras']);
        }*/

        $options = array_filter($options, function ($v) {
            return ($v !== null && $v !== '' && !empty($v)) || is_bool($v) || is_numeric($v);
        });

        $this->notification('ios', $alert, $options);

        return $this;
    }

    /**
     * androidNotification
     *
     * @param string $alert
     * @param array $options
     * @return $this
     */
    public function androidNotification($alert = '', array $options = [])
    {
        $this->notification('android', $alert, $options);

        return $this;
    }

    /**
     * winPhoneNotification
     *
     * @param array|string $alert
     * @param array $options
     * @return $this
     */
    public function winPhoneNotification($alert, array $options = [])
    {
        $this->notification('winphone', $alert, $options);

        return $this;
    }

    public function alert(string $alert)
    {
        $this->notificationAlert = $alert;

        return $this;
    }

    public function getNotification()
    {
        if (!is_null($this->notificationAlert)) {
            $this->notifications['alert'] = $this->notificationAlert;
        }

        $notifications = $this->notifications;

        foreach ($notifications as $platform => &$notification) {
            if (isset($notification['alert']) && empty($notification['alert'])) {
                if ($this->notificationAlert) {
                    $notification['alert'] = $this->notificationAlert;
                } else {
                    throw new \InvalidArgumentException('alert can not be null');
                }
            }
        }

        return $notifications;
    }

    public function callback(array $callbacks)
    {
        $this->callback = $callbacks;

        return $this;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function toArray()
    {
        return array_filter([
            'cid' => $this->getCid(),
            'platform' => $this->getPlatform(),
            'message' => $this->getMessage(),
            'audience' => $this->getAudience(),
            'options' => $this->getOptions(),
            'notification' => $this->getNotification(),
            'callback' => $this->getCallback(),
        ]);
    }
}
