<?php

namespace App\Listeners\Orders;

use App\Events\Orders\Finish;
use App\Models\Users\ParkingSpaceRentalRecord;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscribeRentalFinish
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
        // 用户离场 更新出租记录状态并结算
        $order = $event->order;

        if (!$order->car_rent_id && !$order->car_apt_id) {
            return;
        }

        $record = ParkingSpaceRentalRecord::query()
            ->with('rent')
            ->where('car_apt_id', $order->car_apt_id)
            ->where('car_rent_id', $order->car_rent_id)
            ->where('status', ParkingSpaceRentalRecord::STATUS_PENDING)
            ->whereNull('finished_at')
            ->first();

        if (!$record) {
            return;
        }

        if ($event->is_stop) {
            // $stop_price = 0;
            if ($order->car_stop_id) {
                $record->stop_id = $order->car_stop_id;

                // $stop_price = $order->stop->stop_price;
            }

            // 使用时长：从预约结束开始到离场之间的时长
            $use_time = $order->car_out_time->diffInMinutes($record->subscribe_end_time);

            $stop_amount = $record->rent ? $record->rent->getRentalAmount($use_time) : 0;

            $record->stop_amount = $stop_amount; // 停车费(根据用户设置的价格计算出来的费用，不是用户实际缴费)

            $record->end_time = $order->car_out_time ?? now(); // 离开时间
        }

        $record->finished_at = now();
        $record->status = ParkingSpaceRentalRecord::STATUS_FINISHED;

        $record->amount = $record->subscribe_amount + $record->stop_amount;

        $record->save();
    }
}
