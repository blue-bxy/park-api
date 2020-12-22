<?php

namespace App\Models\Parks;

use App\Models\Dmanger\CarRent;
use App\Models\Dmanger\CarStop;
use App\Models\Users\UserCar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParkVirtualSpace extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'number', 'pic', 'floor', 'is_stop', 'stop_id', 'park_space_id', 'park_area_id', 'park_id', 'park_camera_id', 'car_num'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($space) {
            if ($space->isDirty('is_stop', 'stop_id', 'pic', 'stop_id') && $space->park_space_id) {
                $space->space->update([
                    'pic' => $space->pic,
                    'is_stop' => $space->is_stop,
                    'stop_id' => $space->stop_id,
                    'car_num' => $space->car_num,
                    'status' => $space->is_stop ? 5 : ($space->space->rental()->where('rent_status', 1)->exists() ? 1 : 0)
                ]);
            }
        });
    }

    public function group()
    {
        return $this->hasOneThrough(ParkCameraGroup::class, ParkCamera::class, 'id', 'id', 'park_camera_id', 'group_id');
    }

    public function space()
    {
        return $this->belongsTo(ParkSpace::class, 'park_space_id');
    }

    public function stop()
    {
        return $this->belongsTo(CarStop::class, 'park_space_id', 'park_space_id');
    }

    public function park()
    {
        return $this->belongsTo(Park::class, 'park_id');
    }

    /**
     * rental
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rental()
    {
        return $this->hasOne(CarRent::class, 'park_space_id');
    }

    public function handle(bool $is_stop, string $pic, $car_num = null)
    {
        $result = [
            'pic' => $pic,
            'is_stop' => $is_stop,
            'car_num' => $car_num ?? null
        ];

        $this->update($result);

        $this->stay($car_num);

        if ($group = $this->group) {
            // 可用车位数
            $usable_count = $group->available_count;

            $count = $group->virtualSpace()->where('is_stop', true)->count();

            // 当车位停满后 将运营车位数据标记已停车
            if ($count >= $usable_count) {
                $ids = $group->virtualSpace()
                    ->whereNotNull('park_space_id')
                    ->pluck('park_space_id');

                ParkSpace::query()->whereIn('id', $ids)
                    ->where('is_reserved_type', true)
                    ->where('status', 1)
                    ->update([
                        'status' => 5
                    ]);
            }
        }
    }

    // 停车
    public function stay($car_number)
    {
        // 无效车牌 不操作
        if (!$car_number) return;

        \DB::transaction(function () use ($car_number) {
            $attributes = [
                'park_id' => $this->park_id,
                'car_num' => $car_number
            ];

            $model = CarStop::query()->latest()
                ->whereNull('car_out_time')
                ->firstOrNew($attributes);

            $stop = tap($model, function ($stop) use ($car_number) {
                if (!$stop->user_car_id && $car_number) {
                    $car = UserCar::query()->where('car_number', $car_number)->first();
                    $stop->user_car_id = $car ? $car->getKey() : null;
                    $stop->user_id = $car ? $car->user_id : null;
                    // $stop->car_number = $car_number;
                }

                $stop->park_space_id = $this->park_space_id;

                $stop->car_stop_time = now();

                $stop->save();

                $this->stop_id = $stop->getKey();

                $this->save();
            });

            return $stop;
        });
    }

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
        }
    }
}
