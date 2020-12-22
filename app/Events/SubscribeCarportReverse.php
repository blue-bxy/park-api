<?php

namespace App\Events;

use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscribeCarportReverse
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $subscribe;

    /** @var CarRent */
    public $rent;
    /**
     * Create a new event instance.
     *
     * @param CarApt $subscribe
     *
     * @return void
     */
    public function __construct(CarApt $subscribe)
    {
        $this->subscribe = $subscribe;

        $this->rent = $subscribe->carRent;
    }
}
