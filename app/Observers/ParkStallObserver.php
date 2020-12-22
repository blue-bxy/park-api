<?php

namespace App\Observers;

use App\Exceptions\InvalidArgumentException;
use App\Models\Parks\ParkStall;
use App\Services\ParkSpaceStatisticsService;

class ParkStallObserver
{
    /**
     * @param ParkStall $stall
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function updating(ParkStall $stall) {
        if ($stall->isDirty(['carport_count', 'fixed_carport_count', 'charging_pile_carport',
            'temporary_carport_count'])) {
            $this->quantity($stall);
        }
    }
    private function quantity(ParkStall $stall) {
        if ($stall->carport_count != $stall->fixed_carport_count + $stall->temporary_carport_count) {
            throw new InvalidArgumentException('总车位数必须等于固定车位数与临时车位数之和！');
        }
        $service = new ParkSpaceStatisticsService();
        $service->stallHasAreas($stall);
    }
}
