<?php

namespace App\Models\Bills;

use App\Models\EloquentModel;
use App\Models\Parks\Park;

class OrderAmountDivide extends EloquentModel
{
    protected $fillable = [
        'park_id', 'user_id', 'total_amount', 'fee', 'fee_type', 'property_rate', 'owner_rate',
        'platform_fee', 'property_fee', 'owner_fee'
    ];

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function bill()
    {
        return $this->morphTo('model');
    }
}
