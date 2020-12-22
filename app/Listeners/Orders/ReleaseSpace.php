<?php

namespace App\Listeners\Orders;

use App\Events\Orders\Finish;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ReleaseSpace
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
     * @param  Finish  $event
     * @return void
     */
    public function handle(Finish $event)
    {
        $event->order->releaseSpace();
    }
}
