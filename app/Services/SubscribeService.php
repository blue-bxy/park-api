<?php


namespace App\Services;


use App\Exceptions\ApiResponseException;
use App\Exceptions\OrderPendingPaymentException;
use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\Park;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkSpace;
use App\Models\User;
use App\Models\Users\ParkingSpaceRentalRecord;
use App\Models\Users\UserOrder;

class SubscribeService
{
    /**
     * getParkSpace
     *
     * @param $park_id
     * @param $end_time
     * @param null $space_id
     * @param bool $is_renewal
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @throws ApiResponseException
     */
    public function getParkSpace($park_id, $end_time, $space_id = null, $is_renewal = false)
    {
        /** @var Park $park */
        $park = Park::query()->findOrFail($park_id);

        $remain = $park->getRemain();

        if ($remain['result'] && isset($remain['remain']) && $remain['remain'] <= 0) {
            throw new ApiResponseException('暂无余位可预约', -1);
        }

        $query = $park->spaces()->getQuery();

        $query->addSelect([
                'park_name' => Park::query()->select('park_name')
                    ->whereColumn('park_id', 'id'),
                'area_name' => ParkArea::query()->select('name')
                    ->whereColumn('park_area_id', 'id')
            ]);

        $query->withCount('locks');

        $query->with('rental'); // 出租记录

        $query->whereHas('rental', function ($query) use ($end_time) {
            $start = now()->format('H:i');
            // 开启中
            $query->where('rent_status', 1);
            // 预约时间在出租时间范围内
            $query->where('start', '<=', $start);

            $query->where('stop', '>=', $end_time->format('H:i'));
        });

        // 运营模式：0，无对接，1\2\3
        $query->whereHas('area', function ($query) {
            $query->where('manufacturing_mode', '>', 0);
        });

        $query->where('status', $is_renewal ? 4 : 1)
            ->where('is_reserved_type', 1)
            // ->with('causedRate', 'park.causedRate')
            ->lockForUpdate();

        !is_null($space_id) ? $query->primary($space_id) : $query->inRandomOrder();

        return $query->first();
    }

    /**
     * 获取可用车位
     *
     * @param $park_id
     * @param $end_time
     * @param null $space_id
     * @return ParkSpace|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null
     * @throws ApiResponseException
     */
    public function getAvailableParkSpace($park_id, $end_time, $space_id = null)
    {
        // step1
        $space = $this->getParkSpace($park_id, $end_time, $space_id);

        // step2 第一次无可选车位，第二次进行随机获取
        if (!$space) {
            $space = $this->getParkSpace($park_id, $end_time);
        }

        return $space;
    }

    /**
     * getRent
     *
     * @param $space_id
     * @param \DateTimeInterface|string $end
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|CarRent|null
     */
    public function getRent($space_id, $end)
    {
        $start = now()->format('H:i');

        if ($end instanceof \DateTimeInterface) {
            $end = $end->format('H:i');
        }

        $query = CarRent::query();

        $query->with('space');

        $query->whereHas('space', function ($query) use ($space_id) {
            $query->primary($space_id);
        });
        // 开启中
        $query->where('rent_status', 1);
        // 预约时间在出租时间范围内
        $query->where('start', '<=', $start);

        $query->where('stop', '>=', $end);

        return $query->first();
    }

    public function getPendingPaymentOrder(User $user, $park_id, $car_id)
    {
        $order = $user->orders()->where('park_id', $park_id)
            ->where('user_car_id', $car_id)
            ->whereIn('status', [UserOrder::ORDER_STATE_PAID, UserOrder::ORDER_STATE_PENDING])
            ->where('expired_at', '>', now())
            ->latest('expired_at')
            ->first();

        if ($order) {
            $exception = new ApiResponseException('您还有一个预约订单未完成，请先完成～', 30102);

            $exception->setData([
                'order_no' => $order->payment_no,
            ]);

            throw $exception;
        }

        return false;
    }

    /**
     * getAmount
     *
     * @param $time
     * @param ParkSpace $carport
     * @param CarRent|null $rent
     * @return float|int|mixed
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function getAmount($time, ParkSpace $carport, CarRent $rent = null)
    {
        // 如果存在出租记录，预约金额将已出租单价计算
        if (!is_null($rent)) {
            // 单价，每小时
            $price = $rent->rent_price;

            return intval($time * $price/60);
        }

        return $carport->getRate()->getAmount($time);
    }

    /**
     * 续费
     *
     * @param CarApt $apt
     * @param int $time
     * @return int
     */
    public function getRenewalAmount(CarApt $apt, $time = 30)
    {
        $time_unit = $apt->getParkRateUnit();

        $amount_unit = $apt->getParkRateAmount();

        return intval($time/ $time_unit * $amount_unit);
    }

    /**
     * 添加出租记录
     *
     * 续费时更新
     *
     * @param CarApt $subscribe
     * @return mixed
     */
    public function addRental(CarApt $subscribe)
    {
        return \DB::transaction(function () use ($subscribe) {
            $subscribe->loadMissing('carRent');

            $rental = $subscribe->carRent;

            // 排除自己
            if ($rental->user instanceof User && $rental->user->id == $subscribe->user_id) {
                return false;
            }

            /** @var ParkingSpaceRentalRecord $record */
            $record = tap($subscribe->rentals()->firstOrNew([
                'car_rent_id' => $rental->getKey(),
                'car_apt_id' => $subscribe->getKey()
            ]), function ($record) use ($rental, $subscribe) {
                $amount = $rental->getRentalExpectAmount($subscribe->total_amount);

                $record->fill([
                    'start_time' => $subscribe->apt_start_time,
                    'end_time' => $subscribe->apt_end_time,
                    'subscribe_end_time' => $subscribe->apt_end_time,
                    'rent_time' => $subscribe->apt_time,

                    'user_id' => $subscribe->user_id,
                    'user_car_id' => $subscribe->user_car_id,

                    // 预计
                    'expect_amount' => $amount,
                ]);

                $record->amount = $record->subscribe_amount + $record->stop_amount;

                $record->owner()->associate($rental->user);

                $record->subscribe()->associate($subscribe);

                $record->save();
            });

            return $record;
        });
    }
}
