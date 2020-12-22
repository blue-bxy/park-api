<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Logout
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Authenticatable
     */
    public $user;

    /**
     * Logout constructor.
     * @param Authenticatable $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
