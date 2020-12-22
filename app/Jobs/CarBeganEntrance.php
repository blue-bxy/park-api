<?php

namespace App\Jobs;

use App\Models\Dmanger\CarStop;
use App\Models\Users\UserCar;
use App\Models\Users\UserOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CarBeganEntrance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $results = [];

    protected $sold_id;

    protected $park_id;

    protected $code;

    protected $car_number;

    protected $time;

    protected $pic;

    protected $car_type = 0;
    /**
     * CarBeganEntrance constructor.
     * @param array $results
     */
    public function __construct(array $results)
    {
        $this->results = $results;

        foreach ($results as $key => $result) {
            if (property_exists($this, $key)) {
                $this->$key = $result;
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
        if (!(($this->park_id || $this->code) && $this->car_number)) {
            return;
        }

        // 当天有预约、已付款、未入场
        $query = UserOrder::query();

        $query->with('user', 'car');

        $query->whereHas('car', function ($query) {
                $query->where('car_number', $this->car_number);
            });

        $query->today();

        $query->whereHas('parks', function ($query) {
            $query->when($this->park_id, function ($query) {
                $query->where('id', $this->park_id);
            })->when($this->code, function ($query) {
                $query->where('unique_code', $this->code);
            });
        });

        $query->whereNull('car_in_time')
            ->where('status', UserOrder::ORDER_PAID_STATE_PAID);

        /** @var UserOrder $order */
        $order = $query->latest()->firstOrFail();

        // 添加停车记录
        $attributes = [
            'user_id' => $order->user_id,
            'user_car_id' => $order->user_car_id,
            'park_id' => $order->park_id
        ];

        $values = [
            'car_num' => $this->car_number,
            'car_in_time' => $this->getCarInTime(),
            'car_in_img' => $this->pic,
            'car_type' => $this->car_type
        ];

        if ($this->sold_id) {
            $attributes['sold_id'] = $this->sold_id;
        } else {
            $values['sold_id'] = get_order_no();
        }

        $order->beganEnter($attributes, $values);
    }

    protected function getCarInTime()
    {
        if (is_numeric($this->time)) {
            return Carbon::createFromTimestamp($this->time);
        }

        return Carbon::make($this->time ?? now());
    }
}
