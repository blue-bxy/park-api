<?php

namespace App\Jobs;

use App\Models\Users\UserOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CarDeparture implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $result = [];

    protected $car_number;

    protected $park_id;

    protected $code;

    protected $time;

    protected $pic;

    protected $duration;

    public function __construct(array $result)
    {
        $this->result = $result;

        foreach ($result as $key => $item) {
            if (property_exists($this, $key)) {
                $this->$key = $item;
            }
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 当天有预约、已付款、已入场、未离场
        $query = UserOrder::query();

        $query->with('user', 'car', 'stop');

        $query->has('stop');

        $query->whereHas('car', function ($query) {
            $query->where('car_number', $this->car_number);
        });

        $query->whereHas('parks', function ($query) {
            $query->when($this->park_id, function ($query) {
                $query->where('id', $this->park_id);
            })->when($this->code, function ($query) {
                $query->where('unique_code', $this->code);
            });
        });

        $query->whereNotNull('car_in_time')
            ->whereNull('car_out_time')
            ->where('status', UserOrder::ORDER_PAID_STATE_PAID);

        /** @var UserOrder $order */
        $order = $query->latest()->firstOrFail();

        $out_time = $this->getCarOutTime();

        $stop_time = (int) ($this->duration * 60); // 单位换算成分钟

        // 订单完成
        $order->finish($out_time, $stop_time, $this->pic);
    }

    protected function getCarOutTime()
    {
        if (is_numeric($this->time)) {
            return Carbon::createFromTimestamp($this->time);
        }

        return Carbon::make($this->time ?? now());
    }
}
