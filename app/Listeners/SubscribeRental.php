<?php

namespace App\Listeners;

use App\Events\SubscribeCarport;
use App\Services\SubscribeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscribeRental
{
    public $service;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SubscribeService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  SubscribeCarport  $event
     * @return void
     */
    public function handle(SubscribeCarport $event)
    {
        if ($event->subscribe->car_rent_id) {
            // 增加出租记录
            $this->service->addRental($event->subscribe);
        }
    }
}
