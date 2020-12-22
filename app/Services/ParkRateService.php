<?php


namespace App\Services;


use App\Exceptions\InvalidArgumentException;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use App\Services\Strategies\AreaRateStrategy;
use App\Services\Strategies\ParkRateStrategy;
use App\Services\Strategies\RateStrategy;
use App\Services\Strategies\SpaceRateStrategy;

class ParkRateService {
    /**
     * @var RateStrategy
     */
    private $strategy;

    /**
     * 设置费率类型对应的策略
     * @param $type
     */
    public function setStrategy($type) {
        switch ($type) {
            case ParkRate::TYPE_AREA: $this->strategy = new AreaRateStrategy();
            break;
            case ParkRate::TYPE_SPACE: $this->strategy = new SpaceRateStrategy();
            break;
            default: $this->strategy = new ParkRateStrategy();
        }
    }

    /**
     * @return RateStrategy
     */
    public function getStrategy() {
        return $this->strategy;
    }

    /**
     * create a new record
     * @param array $data
     * @throws InvalidArgumentException
     */
    public function store(array $data) {
        $rate = new ParkRate($data);
        $this->setStrategy($data['type']);
        $space = null;
        if ($data['type'] == ParkRate::TYPE_SPACE) {
            $space = ParkSpace::query()->find($data['park_space_id']);
            if (empty($space)) {
                throw new InvalidArgumentException('请求的车位不存在！');
            }
            $rate->park_area_id = $space->park_area_id;
        }
        $rate->save();
        $this->strategy->publish($rate, $space);
    }

    /**
     * set is_active
     * @param ParkRate $rate
     */
    public function update(ParkRate $rate) {
        if ($rate->isClean('is_active')) {
            return;
        }
        $this->setStrategy($rate->type);
        if ($rate->is_active) {
            $this->strategy->enable($rate);
        } else {
            $this->strategy->disable($rate);
        }
        $rate->save();
    }

    /**
     * delete a record
     * @param ParkRate $rate
     * @throws InvalidArgumentException
     */
    public function delete(ParkRate $rate) {
        if ($rate->is_active != ParkRate::IS_ACTIVE_OFF) {
            throw new InvalidArgumentException('请先停用再操作!');
        }
        $spaces = $rate->spaces()->whereIn('status', array(ParkSpace::STATUS_RESERVING, ParkSpace::STATUS_RESERVED))
            ->pluck('park_spaces.id')->toArray();
        if (!empty($spaces)) {  //排除其他正在运行费率下的车位
            $publishedSpaces = CarRent::query()->whereIn('park_space_id', $spaces)
                ->where('rent_status', '=', CarRent::RENT_START)
                ->pluck('park_space_id')->toArray();
            if (!empty(array_diff($spaces, $publishedSpaces))) {
                throw new InvalidArgumentException('仍有车位在预约，请稍后重试！');
            }
        }
        $rate->delete();
    }

    /**
     * 发布车位
     * @param ParkRate $rate
     */
    public function publish(ParkRate $rate) {
        $this->setStrategy($rate->type);
        $this->strategy->publish($rate);
    }

    /**
     * 取消发布
     * @param ParkRate $rate
     */
    public function unpublish(ParkRate $rate) {
        $this->setStrategy($rate->type);
        $this->strategy->unpublish($rate);
    }

    /**
     * 费率是否正在运行
     * @param ParkRate $rate
     * @return bool
     */
    public function isWork(ParkRate $rate) {
        $this->setStrategy($rate->type);
        return $this->strategy->isWork($rate);
    }

    /**
     * 当前时间是否在费率周期里
     * @param ParkRate $rate
     * @return bool
     */
    public function inPeriod(ParkRate $rate) {
        $this->setStrategy($rate->type);
        return $this->strategy->inPeriod($rate);
    }

    /**
     * 补位
     * @param ParkRate $rate
     */
    public function fill(ParkRate $rate) {
        $this->setStrategy($rate->type);
        $this->strategy->fill($rate);
    }
}
