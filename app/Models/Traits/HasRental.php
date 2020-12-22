<?php

namespace App\Models\Traits;

use App\Models\Users\ParkingSpaceRentalBill;
use App\Models\Users\ParkingSpaceRentalRecord;

trait HasRental
{
    /**
     * 租用记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leases()
    {
        return $this->hasMany(ParkingSpaceRentalRecord::class);
    }

    /**
     * 出租记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function rentals()
    {
        return $this->morphMany(ParkingSpaceRentalRecord::class, 'rental_user');
    }

    /**
     * 出租车位收益账单明细
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rentalIncomeBills()
    {
        return $this->hasMany(ParkingSpaceRentalBill::class);
    }
}
