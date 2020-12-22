<?php

namespace App\Models\Bills;

use App\Models\EloquentModel;
use App\Models\Parks\Park;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkWallet extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'park_id', 'amount', 'reserve_fee', 'parking_fee', 'withdrawal'
    ];

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function records()
    {
        return $this->hasManyThrough(ParkWalletBalance::class, Park::class, 'id', 'park_id', 'park_id');
    }
}
