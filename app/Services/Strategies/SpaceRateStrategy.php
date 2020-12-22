<?php


namespace App\Services\Strategies;


use App\Exceptions\InvalidArgumentException;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;

class SpaceRateStrategy extends RateStrategy {
    /**
     * 发布车位
     * @param ParkRate $rate
     * @param ParkSpace|null $space
     * @return mixed|void
     * @throws InvalidArgumentException
     */
    public function publish(ParkRate $rate, ParkSpace $space = null) {
        if ($space && $space->type != ParkSpace::TYPE_TEMP || empty($space->locks)) {
            throw new InvalidArgumentException('请求的车位不支持当前发布类型！');
        }
        if (!$this->isWork($rate)) {
            return;
        }
        $space = $space ?? $rate->spaces()->first();
        if (empty($space)) {
            throw new InvalidArgumentException('请求的车位不存在！');
        }
        if ($space->status != ParkSpace::STATUS_UNPUBLISHED) {
            throw new InvalidArgumentException('请求的车位暂时无法发布！');
        }
        $this->link($rate, array($space->id));
        $space->status = ParkSpace::STATUS_PUBLISHED;
        $space->save();
    }

    /**
     * 费率启用
     * @param ParkRate $rate
     * @throws InvalidArgumentException
     */
    public function enable(ParkRate $rate) {
        $space = $rate->spaces()->first();
        if (empty($space)) {
            throw new InvalidArgumentException('请求的车位不存在！');
        }
        $this->quantity($rate);
        $this->isSpacePublishedRepeatedly($rate, $space);
        $this->link($rate, array($space->id));
        if (!$this->inPeriod($rate)) {
            return;
        }
        if ($space->status != ParkSpace::STATUS_UNPUBLISHED) {
            throw new InvalidArgumentException('请求的车位暂时无法发布！');
        }
        $space->status = ParkSpace::STATUS_PUBLISHED;
        $space->save();
    }

    /**
     * 停用费率
     * @param ParkRate $rate
     * @return mixed|void
     * @throws InvalidArgumentException
     */
    public function disable(ParkRate $rate) {
        $space = $rate->spaces()->first();
        if (empty($space)) {
            throw new InvalidArgumentException('请求的车位不存在！');
        }
        if (in_array($space->status, array(ParkSpace::STATUS_RESERVING, ParkSpace::STATUS_RESERVED))) {
            throw new InvalidArgumentException('请求的车位正在预约中，请稍后重试！');
        }
        $rate->rents()->update(['rent_status' => CarRent::RENT_STOP]);
        if ($space->status == ParkSpace::STATUS_PUBLISHED) {
            $space->status = ParkSpace::STATUS_UNPUBLISHED;
            $space->save();
        }
    }

    /**
     * 取消发布车位
     * @param ParkRate $rate
     * @return mixed|void
     */
    public function unpublish(ParkRate $rate) {
        if ($this->inPeriod($rate)) {
            return;
        }
        $rate->spaces()->where('status', '=', ParkSpace::STATUS_PUBLISHED)
            ->update(['status' => ParkSpace::STATUS_UNPUBLISHED]);
        $rate->rents()->update(['rent_status' => CarRent::RENT_STOP]);
    }

    /**
     * 车位是否重复发布
     * @param ParkRate $rate
     * @param ParkSpace $space
     * @throws InvalidArgumentException
     */
    private function isSpacePublishedRepeatedly(ParkRate $rate, ParkSpace $space) {
        $query = $space->rates()
            ->where('park_rates.is_active', '=', ParkRate::IS_ACTIVE_ON)
            ->where('type', '=', ParkRate::TYPE_SPACE);
        if ($rate->id) {
            $query->where('park_rates.id', '<>', $rate->id);
        }
        if ($rate->is_workday != ParkRate::IS_WORKDAY_ALL) {
            $query->whereIn('park_rates.is_workday', array($rate->is_workday, ParkRate::IS_WORKDAY_ALL));
        }
        $records = $query->get();
        $this->filter($records, $rate);
        if ($records->isNotEmpty()) {
            throw new InvalidArgumentException('与已有费率冲突！');
        }
    }
}
