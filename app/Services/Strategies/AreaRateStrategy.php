<?php


namespace App\Services\Strategies;


use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;

class AreaRateStrategy extends MultiRateStrategy {
    /**
     * 查询空闲车位
     * @param ParkRate $rate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function queryUnpublishedSpaces(ParkRate $rate) {
        return ParkSpace::query()
            ->where('park_area_id', '=', $rate->park_area_id)
            ->where('status', '=', ParkSpace::STATUS_UNPUBLISHED)
            ->where('type', '=', ParkSpace::TYPE_TEMP);
    }
}
