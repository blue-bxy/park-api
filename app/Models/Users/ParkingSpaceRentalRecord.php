<?php

namespace App\Models\Users;

use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class ParkingSpaceRentalRecord
 * @package App\Models\Users
 *
 * @property Carbon $finished_at
 * @property Carbon $start_time
 * @property Carbon $end_time
 */
class ParkingSpaceRentalRecord extends EloquentModel
{
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_CANCELED = 'canceled';
    const STATUS_FINISHED = 'finished';

    public static $statusMaps = [
        self::STATUS_PENDING => '进行中',
        self::STATUS_CANCELED => '已取消',
        self::STATUS_FINISHED => '已完成'
    ];

    protected $fillable = [
        'user_id', 'rental_user_id', 'rental_user_type', 'car_rent_id', 'user_car_id', 'rent_time',
        'amount', 'fee', 'finished_at', 'start_time', 'end_time', 'status', 'expect_amount', 'car_apt_id',
        'subscribe_end_time', 'subscribe_amount', 'stop_amount', 'stop_id'
    ];

    protected $dates = [
        'finished_at', 'start_time', 'end_time', 'subscribe_end_time'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 业主
    public function owner()
    {
        return $this->morphTo('rental_user');
    }

    public function rent()
    {
        return $this->belongsTo(CarRent::class, 'car_rent_id');
    }

    public function car()
    {
        return $this->belongsTo(UserCar::class, 'user_car_id')->withTrashed();
    }

    public function subscribe()
    {
        return $this->belongsTo(CarApt::class, 'car_apt_id');
    }

    public function getStatusRenameAttribute()
    {
        return self::$statusMaps[$this->status];
    }

    public function getUseTime()
    {
        $use_time = $this->end_time->diffInMinutes($this->start_time);

        if ($this->status == self::STATUS_PENDING && $this->end_time < now()) {
            $use_time = now()->diffInMinutes($this->start_time);
        }

        return $use_time;
    }

    /**
     * 根据订单状态获取相应的金额，进行中：预期金额，已完成：实际金额
     *
     * @return int
     */
    public function getRentalAmount()
    {
        return $this->status == self::STATUS_FINISHED ? $this->amount : $this->expect_amount;
    }

    public function getHasPending()
    {
        return $this->status == self::STATUS_PENDING;
    }
}
