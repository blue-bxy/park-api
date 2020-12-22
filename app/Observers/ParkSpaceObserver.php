<?php

namespace App\Observers;

use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use App\Services\ParkRateService;

class ParkSpaceObserver
{
    public function updating(ParkSpace $space) {
        if ($space->isDirty('status')) {
            $this->setStatus($space);
        }
    }

    private function setStatus(ParkSpace $space) {
        if ($space->type != ParkSpace::TYPE_TEMP) {
            return;
        }

        //补位
        if ($space->status == ParkSpace::STATUS_UNPUBLISHED &&
            $space->getOriginal('status') == ParkSpace::STATUS_PARKING) {
            $rates = ParkRate::query()->with('spaces')
                ->where('park_id', '=', $space->park_id)
                ->where('is_active', '=', ParkRate::IS_ACTIVE_ON)
                ->get();
            $service = new ParkRateService();
            $rates = $rates->filter(function ($rate) use ($service) {
                return $service->inPeriod($rate);
            });
            foreach ($rates as $rate) {
                if ($rate->parking_spaces_count > $rate->spaces()->count()) {
                    if ($rate->park_area_id != $space->park_area_id) {
                        continue;
                    }
                    $service->setStrategy($rate->type);
                    $service->getStrategy()->link($rate, array($space->id));
                    $space->status = ParkSpace::STATUS_PUBLISHED;
                    return;
                }
            }
        }
    }
}
