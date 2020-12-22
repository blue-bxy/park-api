<?php

namespace App\Models\Dmanger;

use App\Models\Bills\OrderAmountDivide;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use App\Models\RentalRateInterface;
use App\Models\Traits\HasRentalRate;
use App\Models\User;
use App\Models\Users\ParkingSpaceRentalRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * Class CarRent
 * @package App\Models\Dmanger
 *
 * @property User $user
 */
class CarRent extends EloquentModel implements RentalRateInterface
{
    use SoftDeletes, HasRentalRate;

    public static $logName = "carRent";

    //出租车位状态
    const RENT_START = 1;   //启用
    const  RENT_STOP = 0;   //停用

    public static $rentTypes = [
        1 => '物业',
        2 => '业主',
        3 => '云平台',
    ];

    // 添加时白名单
    protected $fillable=[
        'park_id','rent_num','rent_price', 'start', 'stop', 'rent_start_time','rent_end_time',
        'rent_status','rent_type_id','rent_no','rent_time','rent_all_price','car_num','park_space_id',
        'pics', 'user_space_id', 'time_unit', 'down_payments', 'down_payments_time', 'is_workday',
        'user_type', 'user_id'
    ];

    protected $casts = [
        'pics' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function (CarRent $rent) {
            if ($rent->isDirty('rent_status')) {
                $rent->space()->update([
                    'status' => $rent->rent_status == 1 ? 1 : 0
                ]);
            }
        });
    }

    public function user()
    {
        return $this->morphTo();
    }

    // 关联停车场模型
    public function parks()
    {
        return $this->belongsTo(Park::class,'park_id','id');
    }

    public function space()
    {
        return $this->belongsTo(ParkSpace::class, 'park_space_id');
    }

    public function rate()
    {
        return $this->belongsTo(ParkRate::class, 'park_rate_id');
    }

    // 关联订单模型
    public function carApt()
    {
        return $this->hasMany(CarApt::class);
    }

    public function apt()
    {
        return $this->hasManyThrough(OrderAmountDivide::class,CarApt::class,'car_rent_id','model_id','id','id');
    }

    public function orders()
    {
        return $this->hasManyThrough(CarApt::class, ParkSpace::class, 'id', 'park_space_id', 'park_space_id');
    }

    public function rentals()
    {
        return $this->hasMany(ParkingSpaceRentalRecord::class, 'car_rent_id');
    }

    public function getWorkday()
    {
        return $this->is_workday;
    }

    public function getStartPeriod()
    {
        return $this->start;
    }

    public function getEndPeriod()
    {
        return $this->stop;
    }

    public function getDepositUnit()
    {
        return $this->down_payments;
    }

    public function getDepositTimeUnit()
    {
        return $this->down_payments_time;
    }

    public function getTimeUnit()
    {
        return $this->time_unit;
    }

    public function getPriceUnit()
    {
        return $this->rent_price;
    }

    public function getRentalUserType()
    {
        return $this->rent_type_id;
    }

    public function getRentTypeAttribute()
    {
        return array_get(self::$rentTypes, $this->rent_type_id, '物业');
    }

    // 搜错查询
    public function scopeSearch(Builder $query,Request $request)
    {

        if ($park_name= $request->input('park_name')) {
            $query->whereHas('parks',function($query) use($park_name){
                $query->where('project_name','like',"%$park_name%");
            });
        }
        // 车位编号
        if($rent_num = $request->input('rent_num')) {
            $query->where('rent_num','like',"%{$rent_num}%");
        }

        // 订单号
        if ($rentNo = $request->input('rent_no')) {
            $query->where('rent_no','like',"%{$rentNo}%");
        }
        // 出租车位类型
        if ($rentTypeId = $request->input('rent_type_id')) {
            $query->where('rent_type_id', $rentTypeId);
        }
        // 判断是否有时间段
        if ($aptStartTime = $request->input('rent_start_time')) {
            $query->where('rent_start_time','>=',$aptStartTime);
        }
        if ($aptEndTime = $request->input('rent_end_time')) {
            $query->where('rent_start_time','<=', $aptEndTime);
        }

        return $query;
    }

    public function getCoversAttribute()
    {
        return collect($this->pics)->map(function ($filename) {
            return $this->getCoverUrl($filename);
        });
    }

    public function getRentalTime()
    {
        return sprintf("%s - %s", $this->start, $this->stop);
    }

    // 获取出租费用
    public function getRentalAmount($time)
    {
        // 单价，每小时
        $price = $this->rent_price;

        return intval($time * $price/60);
    }
}
