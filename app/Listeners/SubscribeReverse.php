<?php

namespace App\Listeners;

use App\Events\SubscribeCarportReverse;
use App\Jobs\SubscribeCancelResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscribeReverse
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
     * @param  SubscribeCarportReverse  $event
     * @return void
     */
    public function handle(SubscribeCarportReverse $event)
    {
        $url = get_park_setting($event->subscribe->park_id, 'callback_url');

        if (!$url) return;

        dispatch(new SubscribeCancelResponse($event->subscribe));
    }
}
