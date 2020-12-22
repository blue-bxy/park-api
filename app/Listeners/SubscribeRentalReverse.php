<?php

namespace App\Listeners;

use App\Events\SubscribeCarportReverse;
use App\Models\Users\ParkingSpaceRentalRecord;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscribeRentalReverse
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
        if ($event->subscribe->car_rent_id || $event->rent) {
            $rent = $event->rent;

            $rental = $rent->rentals()
                ->where('car_apt_id', $event->subscribe->getKey())
                ->where('status', ParkingSpaceRentalRecord::STATUS_PENDING)
                ->first();

            if ($rental) {
                $rental->status = ParkingSpaceRentalRecord::STATUS_CANCELED;
                $rental->expect_amount = 0;
                $rental->save();
            }
        }
    }
}
