<?php

namespace App\Listeners;

use App\Events\SubscribeCarport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscribeNotice
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
     * @param  SubscribeCarport $event
     * @return void
     */
    public function handle(SubscribeCarport $event)
    {
        $is_renewal = $event->order->is_renewal;

        $title = $is_renewal ? '延长预约成功' : '预约成功';

        $park = $event->subscribe->parks;

        $event->order->user->messages()->create([
            'type' => 1,
            'title' => $title,
            'content' => sprintf('您已成功预约[%s]停车场，可在我的行程里查看详细信息', $park->park_name),
        ]);
    }
}
