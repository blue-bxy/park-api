<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Login
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Authenticatable
     */
    public $user;

    /**
     * Login constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
