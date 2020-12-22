<?php

namespace App\Models\Users;

use App\Models\Dmanger\CarRent;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Parks\ParkSpace;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserParkingSpace extends EloquentModel
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_FINISHED = 'finished';
    const STATUS_FAILED = 'failed';

    public static $statusMap = [
        self::STATUS_PENDING => '申请中',
        self::STATUS_FINISHED => '已授权',
        self::STATUS_FAILED => '未授权',
    ];

    public static $idCarTypes = [
        1 => '身份证',
        2 => '驾驶证',
        3 => '护照'
    ];

    protected $fillable = [
        'user_id', 'park_id', 'park_space_id', 'number', 'certificates', 'contracts', 'status', 'remark',
        'property_id', 'admin_id', 'id_card_type', 'id_card_number', 'id_card_name',
        'finished_at', 'failed_at', 'opened_at', 'allowed_at'
    ];

    protected $dates = [
        'finished_at', 'failed_at', 'opened_at', 'allowed_at'
    ];

    protected $casts = [
        'certificates' => 'array',
        'contracts' => 'array'
    ];

    protected $appends = [
        'certificate_covers', 'contract_covers', 'status_rename'
    ];

    public function getStatusRenameAttribute()
    {
        return array_get(self::$statusMap, $this->status, self::STATUS_PENDING);
    }

    public function getIdCardTypeNameAttribute()
    {
        return static::$idCarTypes[$this->id_card_type];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rent()
    {
        return $this->hasOne(CarRent::class, 'user_space_id');
    }

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function setNumberAttribute($number)
    {
        return $this->attributes['number'] = strtoupper($number);
    }

    public function getHasOpenAttribute()
    {
        return $this->hasOpen();
    }

    public function getHasVerifiedAttribute()
    {
        return $this->hasVerified();
    }

    public function hasOpen()
    {
        return ! is_null($this->opened_at);
    }

    public function hasVerified()
    {
        return $this->status == self::STATUS_FINISHED;
    }

    public function hasHandle()
    {
        return $this->status != self::STATUS_PENDING;
    }

    public function getLaunchTimeAttribute()
    {
        $launch_time = $this->created_at;

        if ($this->hasVerified()) {
            $launch_time = $this->finished_at;
        }

        if ($this->status == self::STATUS_FAILED) {
            $launch_time = $this->failed_at;
        }

        return $launch_time;
    }

    public function hasAllowed()
    {
        return !is_null($this->allowed_at);
    }

    public function getCertificateCoversAttribute()
    {
        return collect($this->certificates)->map(function ($cover) {
            return $this->getCoverUrl($cover);
        });
    }

    public function getContractCoversAttribute()
    {
        return collect($this->contracts)->map(function ($cover) {
            return $this->getCoverUrl($cover);
        });
    }
}
