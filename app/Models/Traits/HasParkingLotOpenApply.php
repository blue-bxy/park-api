<?php

namespace App\Models\Traits;

use App\Models\Parks\ParkingLotOpenApply;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasParkingLotOpenApply
{
    /**
     * apply
     *
     * @return HasMany
     */
    public function apply()
    {
        return $this->hasMany(ParkingLotOpenApply::class);
    }
}
