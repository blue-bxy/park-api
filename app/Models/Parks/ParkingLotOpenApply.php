<?php

namespace App\Models\Parks;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ParkingLotOpenApply extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'nickname', 'telephone',
        'village_name', 'village_province', 'village_city', 'village_country', 'village_address',
        'village_telephone', 'longitude', 'latitude',
        'park_id', 'status', 'admin_id', 'remark',
        'processed_at', 'finished_at'
    ];

    protected $dates = [
        'processed_at', 'finished_at'
    ];


    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSED = 'processed';
    const STATUS_FINISHED = 'finished';

    public static $statusMaps = [
        self::STATUS_PENDING => '申请中',
        self::STATUS_PROCESSED => '已受理',
        self::STATUS_FINISHED => '已完成'
    ];

    public function getStatusRenameAttribute()
    {
        return static::$statusMaps[$this->status];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getAddressAttribute()
    {
        return implode('-', $this->only('village_province', 'village_city', 'village_country', 'village_address'));
    }

    public function scopeSearch(Builder $query, Request $request)
    {
        if ($park_name = $request->input('village_name')) {
            $query->where('village_name', 'like', "%$park_name%");
        }

        // 申请时间
        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');

        if ($start_time && $end_time) {
            $query->whereDate('created_at', '<=', $start_time)
                ->whereDate('created_at', '>=', $end_time);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        return $query;
    }
}
