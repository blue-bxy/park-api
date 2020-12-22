<?php

namespace App\Models\Bills;

use App\Models\EloquentModel;
use App\Models\Parks\Park;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class ParkWalletBalance extends EloquentModel
{
    use SoftDeletes;

    const RESERVE_TYPE = 'reserve';
    const PARKING_TYPE = 'parking';
    const WITHDRAWAL_TYPE = 'withdrawal';

    public static $types = [
        self::RESERVE_TYPE => '预约费',
        self::PARKING_TYPE => '停车费',
        self::WITHDRAWAL_TYPE => '提现费'
    ];

    protected $fillable = [
        'park_id', 'amount', 'type', 'trade_type', 'balance', 'order_no'
    ];

    protected $appends = [
        'type_rename'
    ];

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function order()
    {
        return $this->morphTo();
    }

    public function getTypeRenameAttribute()
    {
        return Arr::get(static::$types, $this->type, '未定义');
    }
}
