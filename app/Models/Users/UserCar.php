<?php

namespace App\Models\Users;

use App\Models\EloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCar extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'owner_name', 'car_number', 'frame_number', 'engine_number', 'brand_model',
        'face_license_imgurl', 'back_license_imgurl', 'is_default', 'is_verify', 'verified_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function (UserCar $car) {
            if ($car->trashed()) {
                $car->{$car->getDeletedAtColumn()} = null;
            }
        });
    }

    protected $casts = [
        'is_default' => 'boolean',
        'is_verify' => 'boolean',
    ];

    /**
     * 格式化车牌号
     *
     * @param $value
     */
    public function setCarNumberAttribute($value)
    {
        $this->attributes['car_number'] = format_car_num($value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
