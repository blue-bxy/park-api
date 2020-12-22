<?php

namespace App\Observers;

use App\Exceptions\InvalidArgumentException;
use App\Models\Parks\ParkArea;
use App\Services\ParkSpaceStatisticsService;

class ParkAreaObserver
{
    /**
     * @param ParkArea $area
     * @throws InvalidArgumentException
     */
    public function creating(ParkArea $area) {
        $this->quantity($area);
    }

    /**
     * @param ParkArea $area
     * @throws InvalidArgumentException
     */
    public function updating(ParkArea $area) {
        if ($area->isDirty(['parking_places_count', 'temp_parking_places_count',
            'fixed_parking_places_count', 'charging_pile_parking_places_count'])) {
            $this->quantity($area);
        }
    }

    /**
     * 车位数量检查
     * @param ParkArea $area
     * @throws InvalidArgumentException
     */
    private function quantity(ParkArea $area) {
        if ($area->parking_places_count != $area->fixed_parking_places_count + $area->temp_parking_places_count) {
            throw new InvalidArgumentException('总车位数必须等于固定车位数与临时车位数之和！');
        }
        $service = new ParkSpaceStatisticsService();
        $service->areaBelongToStall($area);
        if ($area->id) {
            $service->areaHasSpaces($area);
        }
    }
}
