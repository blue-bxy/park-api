<?php


namespace App\Services\Strategies;


use App\Exceptions\InvalidArgumentException;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkSpaceLock;
use Illuminate\Database\Eloquent\Collection;

abstract class MultiRateStrategy extends RateStrategy {
    /**
     * conflicting rates
     * @var Collection
     */
    protected $records;

    /**
     * unpublished spaces
     * @var Collection
     */
    protected $spaces;

    /**
     * 发布车位
     * @param ParkRate $rate
     * @param ParkSpace|null $space
     * @return mixed|void
     * @throws InvalidArgumentException
     */
    public function publish(ParkRate $rate, ParkSpace $space = null) {
        if (!$this->isWork($rate)) {
            return;
        }
        $this->enable($rate);
    }

    /**
     * 取消发布车位
     * @param ParkRate $rate
     * @return mixed|void
     */
    public function unpublish(ParkRate $rate) {
        if ($this->isWork($rate)) {
            return;
        }
        $this->disable($rate);
    }

    /**
     * 启用费率
     * @param ParkRate $rate
     * @throws InvalidArgumentException
     */
    public function enable(ParkRate $rate) {
        $this->quantity($rate);
        $spaces = $this->queryUnpublishedSpaces($rate)->pluck('id');
        $this->assignSpaces($rate, $spaces->toArray());
    }

    /**
     * 停用费率
     * @param ParkRate $rate
     */
    public function disable(ParkRate $rate) {
        $this->freeSpaces($rate);
    }

    /**
     * 补位
     * @param ParkRate $rate
     */
    public function fill(ParkRate $rate) {
        $spaces = $this->queryUnpublishedSpaces($rate)->with('locks')->get();
        if ($spaces->isEmpty()) {
            return;
        }
        foreach ($spaces as $item) {
            if (empty($item->locks)) {
                $space = $item;
                break;
            }
        }
        $space = $space ?? $spaces->first();
        $this->link($rate, array($space->id));
        $space->status = ParkSpace::STATUS_PUBLISHED;
        $space->save();
    }

    /**
     * 查询空闲车位
     * @param ParkRate $rate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function queryUnpublishedSpaces(ParkRate $rate) {
        return ParkSpace::query()
            ->where('park_id', '=', $rate->park_id)
            ->where('status', '=', ParkSpace::STATUS_UNPUBLISHED)
            ->where('type', '=', ParkSpace::TYPE_TEMP);
    }

    /**
     * 分配车位（优先无锁）
     * @param ParkRate $rate
     * @param array $spaces
     */
    protected function assignSpaces(ParkRate $rate, array $spaces) {
        if (count($spaces) > $rate->parking_spaces_count) { //优先分配无锁的车位
            $spacesHaveLock = ParkSpaceLock::query()
                ->whereIn('id', $spaces)
                ->pluck('id')
                ->toArray();
            $spacesDontHaveLock = array_diff($spaces, $spacesHaveLock);
            if (count($spacesDontHaveLock) > $rate->parking_spaces_count) { //全是无锁的
                shuffle($spacesDontHaveLock);
                $spaces = array_slice($spacesDontHaveLock, 0, $rate->parking_spaces_count);
            } else {    //无锁车位不足时
                shuffle($spacesHaveLock);
                $spaces = array_merge($spacesDontHaveLock, array_slice($spacesHaveLock, 0, $rate->parking_spaces_count - count($spacesDontHaveLock)));
            }
        }
        $this->link($rate, $spaces);
        ParkSpace::query()->whereIn('id', $spaces)->update(['status' => ParkSpace::STATUS_PUBLISHED]);
    }

    protected function freeSpaces(ParkRate $rate) {
        $rate->spaces()->where('status', '=', ParkSpace::STATUS_PUBLISHED)
            ->update(['status' => ParkSpace::STATUS_UNPUBLISHED]);
        $rate->rents()->update(['rent_status' => CarRent::RENT_STOP]);
    }
}
