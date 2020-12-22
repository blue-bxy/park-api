<?php


namespace App\Services\Strategies;


use App\Exceptions\InvalidArgumentException;
use App\Models\Admin;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

abstract class RateStrategy {
    /**
     * 发布车位
     * @param ParkRate $rate
     * @param ParkSpace|null $space
     * @return mixed
     */
    abstract public function publish(ParkRate $rate, ParkSpace $space = null);

    /**
     * 取消发布
     * @param ParkRate $rate
     * @return mixed
     */
    abstract public function unpublish(ParkRate $rate);

    /**
     * 启用费率
     * @param ParkRate $rate
     * @return mixed
     */
    abstract public function enable(ParkRate $rate);

    /**
     * 停用费率
     * @param ParkRate $rate
     * @return mixed
     */
    abstract public function disable(ParkRate $rate);

    /**
     * 补位
     * @param ParkRate $rate
     */
    public function fill(ParkRate $rate) {}

    /**
     * 关联park_rates与park_spaces
     * @param ParkRate $rate
     * @param array $spaces (ids of park_spaces)
     * @param int $status
     */
    public function link(ParkRate $rate, array $spaces, int $status = CarRent::RENT_START) {
        //set rent_status of records in car_rents
        $records = CarRent::query()
            ->select(['id', 'park_space_id'])
            ->where('park_rate_id', '=', $rate->id)
            ->whereIn('park_space_id', $spaces)
            ->get();
        if ($records->isNotEmpty()) {
            CarRent::query()->whereIn('id', $records->pluck('id')->toArray())
                ->update(['rent_status' => $status]);
        }

        //create new records
        $spaces = array_diff($spaces, $records->pluck('park_space_id')->toArray());
        if (empty($spaces)) {
            return;
        }
        $data = array();
        foreach ($spaces as $space) {
            array_push($data, [
                'park_rate_id' => $rate->id,
                'user_id' => $rate->publisher_id,
                'user_type' => $rate->publisher_type,
                'park_id' => $rate->park_id,
                'rent_price' => $rate->payments_per_unit,
                'is_workday' => $rate->is_workday,
                'down_payments' => $rate->down_payments,
                'down_payments_time' => $rate->down_payments_time,
                'time_unit' => $rate->time_unit,
                'start' => str_pad($rate->start_period, 2, '0', STR_PAD_LEFT).':00',
                'stop' => str_pad($rate->end_period, 2, '0', STR_PAD_LEFT).':00',
                'rent_status' => $status,
                'rent_type_id' => $rate->publisher_type == Admin::class ? 3 : 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'park_space_id' => $space,
                'rent_no' => get_order_no()
            ]);
        }
//        $rate->rents()->saveMany($data);
        DB::table('car_rents')->insert($data);  //CarRent有绑定事件
    }

    /**
     * 取消关联
     * @param ParkRate $rate
     */
    public function unlink(ParkRate $rate) {
        CarRent::query()->where('park_rate_id', '=', $rate->id)
            ->whereHas('space', function (Builder $query) {
                $query->whereIn('status', array(ParkSpace::STATUS_UNPUBLISHED, ParkSpace::STATUS_PUBLISHED, ParkSpace::STATUS_PARKING));
            })->update(['rent_status' => CarRent::RENT_STOP]);
    }

    /**
     * 费率是否正在运行
     * @param ParkRate $rate
     * @return bool
     */
    public function isWork(ParkRate $rate) {
        if ($rate->is_active != ParkRate::IS_ACTIVE_ON) {
            return false;
        }
        return $this->inPeriod($rate);
    }

    /**
     * 当前时间是否在费率周期里
     * @param ParkRate $rate
     * @return bool
     */
    public function inPeriod(ParkRate $rate) {
        $hour = now()->hour;
        if ($hour < $rate->start_period || $hour >= $rate->end_period) {
            return false;
        }
        $isWeekday = now()->isWeekday();
        if ($rate->is_workday == ParkRate::IS_WORKDAY_TRUE && !$isWeekday ||
            $rate->is_workday == ParkRate::IS_WORKDAY_FALSE && $isWeekday) {
            return false;
        }
        return true;
    }

    /**
     * 数量检查
     * @param ParkRate $rate
     * @throws InvalidArgumentException
     */
    protected function quantity(ParkRate $rate) {
        $records = $this->conflict($rate);
        $spaces = ParkSpace::query()
            ->selectRaw('count(*) as quantity, park_area_id')
            ->where('park_id', '=', $rate->park_id)
            ->where('type', '=', ParkSpace::TYPE_TEMP)
            ->groupBy('park_area_id')
            ->get();
        $quantities = array(0);   //各区域车位数量，键名为区域id，0表示全车场
        foreach ($spaces as $space) {
            $quantities[$space->park_area_id] = $space->quantity;
            $quantities[0] += $space->quantity;
        }
        //扣除各区域和车场的车位数量
        $records->add($rate);
        foreach ($records as $record) {
            $quantities[$record->park_area_id] -= $record->parking_spaces_count;
            if ($record->park_area_id > 0) {
                $quantities[0] -= $record->parking_spaces_count;
            }
        }
        if ($quantities[$rate->park_area_id] < 0 || $quantities[0] < 0) {
            throw new InvalidArgumentException('剩余车位数不足！');
        }
    }

    /**
     * 冲突的费率
     * @param ParkRate $rate
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function conflict(ParkRate $rate) {
        $query = ParkRate::query()->where('park_id', '=', $rate->park_id)
            ->where('is_active', '=', ParkRate::IS_ACTIVE_ON);
        if ($rate->id) {
            $query->where('id', '<>', $rate->id);
        }
        if ($rate->is_workday != ParkRate::IS_WORKDAY_ALL) {
            $query->whereIn('park_rates.is_workday', array($rate->is_workday, ParkRate::IS_WORKDAY_ALL));
        }
        $records = $query->get();
        $this->filter($records, $rate);
        return $records;
    }

    /**
     * 过滤出时间段重复的记录
     * @param Collection $records
     * @param ParkRate $rate
     */
    public function filter(Collection $records, ParkRate $rate) {
        $records->filter(function ($record) use ($rate) {
            if ($record->start_period >= $rate->end_period) {
                return false;
            }
            if ($record->end_period <= $rate->start_period) {
                return false;
            }
            return true;
        });
    }
}
