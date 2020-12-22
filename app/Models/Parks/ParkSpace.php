<?php

namespace App\Models\Parks;

use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use App\Models\Dmanger\CarStop;
use App\Models\EloquentModel;
use App\Models\Financial\BookingFee;
use App\Models\Traits\HasMap;
use App\Models\Traits\HasParkRate;
use App\Models\Users\ParkingSpaceRentalRecord;
use App\Models\Users\UserCar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ParkSpace
 * @package App\Models\Parks
 *
 * @property CarRent $rental
 * @property \Illuminate\Database\Eloquent\Relations\BelongsTo|Park $park
 */
class ParkSpace extends EloquentModel
{
    //车位类型
    const TYPE_FIXED = 1;   //固定
    const TYPE_TEMP  = 2;    //临停
    const TYPES = [
        self::TYPE_FIXED    => '固定',
        self::TYPE_TEMP     => '临停'
    ];

    //车位类别
    const CATEGORY_CHARGING_PILE = 1;   //充电桩车位
    const CATEGORY_ORDINARY      = 0;   //普通车位
    const CATEGORIES = [
        self::CATEGORY_ORDINARY         => '普通',
        self::CATEGORY_CHARGING_PILE    => '充电桩'
    ];

    //车位状态
    const STATUS_UNPUBLISHED = 0;   //未发布
    const STATUS_PUBLISHED   = 1;   //已发布
    const STATUS_DISABLED    = 2;   //停用
    const STATUS_RESERVING   = 3;   //预约中
    const STATUS_RESERVED    = 4;   //已预约
    const STATUS_PARKING     = 5;   //已使用
    const STATUSES = [
        self::STATUS_UNPUBLISHED    => '未发布',
        self::STATUS_PUBLISHED      => '已发布',
        self::STATUS_DISABLED       => '停用',
        self::STATUS_RESERVING      => '预约中',
        self::STATUS_RESERVED       => '已预约',
        self::STATUS_PARKING        => '已停车'
    ];

    use SoftDeletes, HasParkRate, HasMap;

    protected $fillable = [
        'park_id', 'number', 'map_unique_id', 'area_code', 'longitude', 'latitude', 'type',
        'rent_type', 'status', 'category', 'is_reserved_type', 'remark', 'park_area_id',
        'is_stop', 'pic', 'stop_id', 'device_unique_id', 'floor', 'car_num'
    ];

    protected $attributes = [
        'rent_type'        => 0,
        'is_reserved_type' => 1
    ];

    protected $casts = [
        'is_stop' => 'boolean'
    ];

    /**
     * 关联park_area表
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(ParkArea::class, 'park_area_id');
    }

    /**
     * 蓝牙
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function bluetooth()
    {
        return $this->morphedByMany(ParkBluetooth::class, 'device', 'park_space_has_devices');
    }

    /**
     * 摄像头
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function cameras()
    {
        return $this->morphedByMany(ParkCamera::class, 'device', 'park_space_has_devices');
    }

    /**
     * 地锁
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function locks()
    {
        return $this->hasOne(ParkSpaceLock::class);
    }

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    public function rates() {
        return $this->belongsToMany(ParkRate::class, 'car_rents', 'park_space_id', 'park_rate_id');
    }

    /**
     * 当前费率
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function causedRate()
    {
        return $this->rates()->period()->weekday()->active()->limit(1);
    }

    /**
     * 最新的预约记录
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function carApt()
    {
        return $this->hasOne(CarApt::class)
            // ->where('apt_start_time', '<', now())
            ->where('apt_end_time', '>', now())
            ->orderBy('apt_start_time', 'desc');
    }

    public function stops()
    {
        return $this->hasMany(CarStop::class);
    }

    public function stop()
    {
        return $this->belongsTo(CarStop::class);
    }

    /**
     * 当前流程下的记录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function record()
    {
        return $this->hasOne(ParkingSpaceRentalRecord::class, 'stop_id', 'stop_id');
    }

    /**
     * 最新的占用车辆
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carStop()
    {
        return $this->stop();
    }

    /**
     * 车位发布者
     */
    public function spaceType()
    {
        return $this->rates();
    }

    /**
     * 查询范围过滤
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        if ($park_area_id = $request->input('park_area_id')) {
            $query->where('park_area_id', '=', $park_area_id);
        }
        if ($number = $request->input('number')) {
            $query->where('number', '=', $number);
        }
        if (!is_null($status = $request->input('status'))) {
            $query->where('status', '=', $status);
        }
        if ($hasCamera = $request->input('has_camera')) {
            $query->whereHas('cameras');
        }
        if ($hasBluetooth = $request->input('has_bluetooth')) {
            $query->whereHas('bluetooth');
        }
        if ($hasLock = $request->input('has_lock')) {
            $query->whereHas('locks');
        }
        return $query;
    }

    public function mapSpaceId()
    {
        return $this->map_unique_id;
    }

    public function scopePrimary(Builder $query, $id)
    {
        return $query->where(function ($query) use ($id) {
            return $query->orWhere($this->getTable().'.id', $id)->orWhere('map_unique_id', $id);
        });
    }

    // 停车
    public function stay(string $car_number)
    {
        $callback = function ($stop) use ($car_number) {
            $car = UserCar::query()->where('car_number', $car_number)->first();

            $stop->fill([
                'park_id' => $this->park_id
            ]);

            $user_id = $car->user_id ?? null;

            $stop->user()->associate($user_id);
            $stop->userCar()->associate($car);
            $stop->rental()->associate($this->rental);

            $stop->save();

            $this->stop_id = $stop->getKey();

            $this->save();

            // 检查车位出租
            if ($this->rental && $user_id) {
                $this->record()->create([
                    'rental_user_id' => $this->rental->user_id,
                    'user_id' => $user_id,
                    'user_car_id' => $car->id,
                    'stop_id' => $stop->getKey(),
                ]);
            }

        };

        return DB::transaction(function () use ($car_number, $callback) {
            $stop = $this->stop()->latest()->firstOrNew(['car_num' => $car_number]);

            $callback($stop);

            return $stop;
        });

    }

    // 离开
    public function leave(array $result = [])
    {
        $stop = $this->stop()
            ->whereNotNull('car_stop_time')
            ->whereNull('car_out_time')
            ->first();

        if ($stop) {
            $stop->car_out_time = now();

            $stop->stop_time = now()->diffInMinutes($stop->car_stop_time);

            $stop->save();

            if ($this->record()->exists()) {
                try {
                    // 根据所停位置获取价格表
                    $rate = $this->getRate();

                    $amount = (int) $rate->payments_per_unit * $stop->stop_time;
                    // 业主结算手续费比例
                    $fee = BookingFee::getOwnerFee($amount);

                    $this->record()->update([
                        'rent_time' => $stop->stop_time,
                        'amount' => $amount,
                        'fee' => $fee
                    ]);
                } catch (\Exception $e) {
                    logger($e->getMessage());
                }
            }
        }
    }
}
