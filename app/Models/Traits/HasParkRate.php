<?php

namespace App\Models\Traits;

use App\Exceptions\InvalidArgumentException;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;

trait HasParkRate
{
    /**
     * rates
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function rates()
    {
        return $this->morphToMany(ParkRate::class, 'model', 'model_has_park_rates');
    }

    public function causedRate()
    {
        return $this->rates()
            ->latest('payments_per_unit')
            ->latest('down_payments')
            ->limit(1)
            ->active()->weekday();
    }

    /**
     * rental
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rental()
    {
        return $this->hasOne(CarRent::class, 'park_space_id');
    }

    /**
     * getRate
     *
     * @return ParkRate
     * @throws InvalidArgumentException
     */
    public function getRate()
    {
        $rate = $this->rental()
            ->where('rent_status', true)
            ->first();

        // 如果车位没有设置，向车场拿
        // if ($this instanceof ParkSpace && !$rate) {
        //     $rate = $this->park->causedRate()->first();
        // }

        if (!$rate) {
            throw new InvalidArgumentException('价格数据不存在');
        }

        return $rate;
    }
}
