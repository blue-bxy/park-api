<?php

namespace App\Models\Traits;

use App\Models\Financial\BookingFee;
use App\Models\User;

trait HasRentalRate
{
    public function getRentalFee()
    {
        // 出租手续费
        $type = $this->user instanceof User ? 1 : 2;

        return $this->getRentalFeeByType($type);
    }

    public function fee()
    {
        return BookingFee::getFees($this->park_id);
    }

    public function getRentalFeeByType($type)
    {
        return collect($this->fee()->apt)->filter(function ($apt) use ($type) {
            return $apt['type'] == $type;
        })->first();
    }

    /**
     * 根据用户出租金额，计算预期金额
     *
     * @param $amount
     * @return int
     */
    public function getRentalExpectAmount($amount)
    {
        $fees = $this->getRentalFee();

        $expect_amount = 0;

        if (!$fees) {
            return $expect_amount;
        }

        // 百分比
        if ($owner = $fees['owner']) {
            $expect_amount = (int) ($owner * $amount/100);
        }

        return $expect_amount;
    }
}
