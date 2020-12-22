<?php


namespace App\Packages\JPush;


use App\Packages\JPush\Clients\PushClient;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Notifications\Events\NotificationFailed;
use Illuminate\Notifications\Notification;

class JPushChannel
{
    protected $client;

    protected $events;

    public function __construct(PushClient $client, Dispatcher $events)
    {
        $this->client = $client;

        $this->events = $events;
    }

    public function send($notifiable, Notification $notification)
    {
        if ($notifiable->routeNotificationFor('jpush', $notification)) {
            return;
        }

        $message = $notification->toJpush($notifiable);

        try {
            $this->client->send($message->toArray());

        } catch (\Throwable $exception) {
            $this->events->dispatch(
                new NotificationFailed($notifiable, $notification, 'jpush-notifications')
            );
        }
    }
}
