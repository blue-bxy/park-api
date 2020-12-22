<?php

namespace App\Listeners;

use App\Events\SubscribeCarport;
use App\Jobs\SubscribeResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscribeSend
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubscribeCarport  $event
     * @return void
     */
    public function handle(SubscribeCarport $event)
    {
        $url = get_park_setting($event->subscribe->park_id, 'callback_url');

        if (!$url) return;

        dispatch(new SubscribeResponse($event->order));
    }
}
