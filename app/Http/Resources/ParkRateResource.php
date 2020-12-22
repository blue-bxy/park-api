<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkRateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'is_workday' => $this->getWorkday(),
            'start_period' => $this->getStartPeriod(),
            'end_period' => $this->getEndPeriod(),
            'down_payments' => $this->getDepositUnit(),
            'down_payments_time' => $this->getDepositTimeUnit(),
            'time_unit' => $this->getTimeUnit(),
            'payments_per_unit' => $this->getPriceUnit(),
            'rental_user_type' => $this->getRentalUserType(),
        ];
    }
}
