<?php


namespace App\Services;



use App\Jobs\JPushSendNotification;
use App\Models\Users\Message;
use App\Packages\JPush\JPushMessage;

class NotificationService
{
    public function send(Message $message)
    {
        // sent_type = 1 APP通知 立即发送
        if ($message->isNow() && $message->send_type == 1) {
            $this->sendNow($message);
        } else {
            // 定时发送
            dispatch(new JPushSendNotification($message));
        }
    }

    public function sendNow(Message $message)
    {
        $jpush = (new JPushMessage);

        $jpush->platform($message->platform);

        $jpush->alert($message->content);

        if ($message->platform == 'ios' || $message->platform == 'all') {
            $jpush->iosNotification([
                'title' => $message->title,
                'alert' => $message->content
            ], [
                'badge' => '+1',
                'sound' => ''
            ])->options([
                'apns_production' => app()->isProduction()
            ]);
        }

        if ($message->platform == 'android' || $message->platform == 'all') {
            $jpush->androidNotification($message->content, [
                'title' => $message->title
            ]);
        }

        $jpush->options([
            'sendno' => $message->no
        ]);

        try {
            $response = app('jpush.push')->send($jpush->toArray());

            //
        } catch (\Exception $exception) {
            //
        }

    }
}
