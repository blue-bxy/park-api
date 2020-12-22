<?php

namespace App\Events\Orders;

use App\Models\Users\UserOrder;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Finish
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public $is_stop;

    /**
     * Create a new event instance.
     *
     * @param UserOrder $order
     * @param bool $is_stop
     *
     * @return void
     */
    public function __construct(UserOrder $order, $is_stop = true)
    {
        $this->order = $order;

        $this->is_stop = $is_stop;
    }
}
