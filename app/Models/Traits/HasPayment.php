<?php

namespace App\Models\Traits;

use App\Models\Payment;
use App\Models\Users\UserRefund;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasPayment
{
    /**
     * payment
     *
     * @return HasMany
     */
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * refunds
     *
     * @return HasMany
     */
    public function refunds()
    {
        return $this->hasMany(UserRefund::class);
    }
}
