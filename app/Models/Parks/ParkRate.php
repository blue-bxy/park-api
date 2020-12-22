<?php

namespace App\Models\Parks;

use App\Models\Admin;
use App\Models\Dmanger\CarRent;
use App\Models\EloquentModel;
use App\Models\RentalRateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class ParkRate extends EloquentModel implements RentalRateInterface
{
    //时间类型
    const IS_WORKDAY_FALSE = 0; //非工作日
    const IS_WORKDAY_TRUE = 1;  //工作日
    const IS_WORKDAY_ALL = 2;   //全部

    //费率状态
    const IS_ACTIVE_OFF =0; //停用
    const IS_ACTIVE_ON =1;  //启用

    //费率类型
    const TYPE_PARK =0;     //车场
    const TYPE_AREA =1;     //区域
    const TYPE_SPACE =2;    //车位

    use SoftDeletes;

    protected $fillable = [
        'no', 'name', 'is_workday', 'start_period', 'end_period',
        'down_payments', 'down_payments_time', 'time_unit', 'payments_per_unit',
        'first_day_limit_payments', 'is_active', 'parking_spaces_count',
        'publisher_type', 'publisher_id', 'park_id', 'park_area_id', 'type'
    ];

    public function getWorkday()
    {
        return $this->is_workday;
    }

    public function getStartPeriod()
    {
        return $this->start_period;
    }

    public function getEndPeriod()
    {
        return $this->end_period;
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
        return $this->payments_per_unit;
    }

    public function getRentalUserType()
    {
        return $this->publisher_type == Admin::class ? 3 : 1;
    }

    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', strtotime($value));
    }

    /**
     * 所属停车场
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    /**
     * 所属区域
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area() {
        return $this->belongsTo(ParkArea::class, 'park_area_id');
    }

    /**
     * 发布方（平台、停车场、车主）
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function publisher()
    {
        return $this->morphTo();
    }

    /**
     * 绑定车位
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function spaces()
    {
        return $this->belongsToMany(ParkSpace::class, CarRent::class, 'park_rate_id', 'park_space_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rents() {
        return $this->hasMany(CarRent::class, 'park_rate_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeWeekday(Builder $query)
    {
        $query->where(function ($query) {
            $is_weekday = now()->isWeekday();

            $query->orWhere('is_workday', $is_weekday)->orWhere('is_workday', 2);
        });

        return $query;
    }

    public function scopePeriod(Builder $query) {
        $hour = now()->hour;
        return $query->where('start_period', '<=', $hour)
            ->where('end_period', '>', $hour);
    }

    // 时间单位:分
    public function getAmount($time)
    {
        // 首付
        $amount = $this->down_payments;

        $time = max(0, $time - $this->down_payments_time);

        // 正常收费 每30分钟10元，上限100元
        // 210/60
        $amount += intval($time/ $this->time_unit * $this->payments_per_unit);

        return min($amount, $this->first_day_limit_payments);
    }

    // 预约费用，默认30分钟起
    public function renewalAmount($time = 30)
    {
        return intval($time/ $this->time_unit * $this->payments_per_unit);
    }

    /**
     * 查询范围过滤
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        if ($park_id = $request->input('park_id')) {
            $query->where('park_id', '=', $park_id);
        }
        if ($project_name = $request->input('project_name')) {
            $query->whereHas('park', function (Builder $query) use ($project_name) {
                $query->where('project_name', 'like', "%$project_name%");
            });
        }
        return $query;
    }

    /**
     * 补位：当发布车位被外来车辆占用时，预约车辆入场释放车位，离场关闭车位
     *
     * @param $park_id
     * @return ParkRate|null
     */
    public static function unexpected($park_id)
    {
        return ParkRate::query()->where('park_id', $park_id)
            ->withCount(['spaces' => function ($query) {
                // 车位被
                $query->where('status', 5)->where('is_stop', true)
                    ->whereNotNull('stop_id')
                    ->whereHas('stop', function ($query) {
                        $query->whereNull('user_id');
                    });
            }])
            ->active() // 开放
            // ->weekday()->period() // 符合时间
            ->first();
    }
}
