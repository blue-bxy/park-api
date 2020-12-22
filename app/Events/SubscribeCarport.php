<?php

namespace App\Events;

use App\Models\Dmanger\CarAptOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscribeCarport
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public $subscribe;

    /**
     * Create a new event instance.
     *
     * @param CarAptOrder $order
     *
     * @return void
     */
    public function __construct(CarAptOrder $order)
    {
        $this->order = $order;

        $this->subscribe = $order->carApt;
    }
}
