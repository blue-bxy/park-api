<?php


namespace App\Services;


use App\Exceptions\InvalidArgumentException;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkRate;
use App\Models\Parks\ParkSpace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ParkSpaceService {
    const SET_STATUS = [
        ParkSpace::STATUS_UNPUBLISHED => array(ParkSpace::STATUS_DISABLED, ParkSpace::STATUS_PARKING),
        ParkSpace::STATUS_PUBLISHED => array(ParkSpace::STATUS_UNPUBLISHED, ParkSpace::STATUS_DISABLED, ParkSpace::STATUS_PARKING),
        ParkSpace::STATUS_DISABLED => array(ParkSpace::STATUS_UNPUBLISHED),
        ParkSpace::STATUS_RESERVING => array(ParkSpace::STATUS_PARKING),
        ParkSpace::STATUS_RESERVED => array(ParkSpace::STATUS_PARKING),
        ParkSpace::STATUS_PARKING => array(ParkSpace::STATUS_UNPUBLISHED, ParkSpace::STATUS_DISABLED)
    ];

    /**
     * @var ParkArea
     */
    private $area;

    /**
     * @var Collection
     */
    private $spaces;

    public function update(ParkSpace $space) {
        if ($space->isDirty(['number', 'type', 'category'])) {
            $this->check($space);
        }
        DB::transaction(function () use ($space) {
            $this->setStatus($space);
            $space->save();
        });
    }

    /**
     * @param ParkSpace $space
     * @throws InvalidArgumentException
     */
    private function check(ParkSpace $space) {
        $this->init($space->park_area_id, $space->id);
        $this->number($space);
        $this->type($space);
        $this->category($space);
    }

    /**
     * 设置车位状态
     * @param ParkSpace $space
     * @throws InvalidArgumentException
     */
    private function setStatus(ParkSpace $space) {
        if ($space->isClean('status')) {
            return;
        }
        if (!in_array($space->status, self::SET_STATUS[$space->getOriginal('status')] ?? array())) {
            throw new InvalidArgumentException('当前状态禁止变更到指定状态');
        }
        if ($space->status == ParkSpace::STATUS_DISABLED) {
            $this->disable($space);
        } elseif ($space->status == ParkSpace::STATUS_UNPUBLISHED) {
            if ($space->getOriginal('status') != ParkSpace::STATUS_DISABLED) {
                $this->unpublish($space);
            }
        }
    }

    /**
     * 停用
     * @param ParkSpace $space
     */
    private function disable(ParkSpace $space) {
        if ($space->getOriginal('type') == ParkSpace::TYPE_FIXED) {
            //固定车位停用
            if (empty($rental = $space->rental)) {
                $rental->rent_status = CarRent::RENT_STOP;
                $rental->save();
            }
            return;
        }
        $service = new ParkRateService();
        $rates = $space->rates()->get();
        foreach ($rates as $rate) {
            if ($rate->type == ParkRate::TYPE_SPACE) {
                $rate->is_active = ParkRate::IS_ACTIVE_OFF;
                $rate->save();
            } else {
                if ($service->isWork($rate)) {
                    $rate->spaces()->detach($space->id);
                    $rate->rents()->where('park_space_id', '=', $space->id)->update(['rent_status' => CarRent::RENT_STOP]);
                    $service->fill($rate);
                }
            }
        }
    }

    /**
     * 取消发布
     * @param ParkSpace $space
     */
    private function unpublish(ParkSpace $space) {
        if ($space->getOriginal('type') == ParkSpace::TYPE_FIXED) {
            //固定车位停用
            if (empty($rental = $space->rental)) {
                $rental->rent_status = CarRent::RENT_STOP;
                $rental->save();
            }
            return;
        }
        $service = new ParkRateService();
        $rates = $space->rates()->get();
        foreach ($rates as $rate) {
            if ($service->isWork($rate)) {
                if ($rate->type == ParkRate::TYPE_SPACE) {
                    $rate->is_active = ParkRate::IS_ACTIVE_OFF;
                    $rate->save();
                } else {
                    $rate->spaces()->detach($space->id);
                    $rate->rents()->where('park_space_id', '=', $space->id)->update(['rent_status' => CarRent::RENT_STOP]);
                    $service->fill($rate);
                }
                return;
            }
        }
    }

    /**
     * @param int $parkAreaId
     * @param int|null $id
     * @throws InvalidArgumentException
     */
    private function init(int $parkAreaId, int $id = null) {
        $this->area = ParkArea::query()->find($parkAreaId);
        if (empty($this->area)) {
            throw new InvalidArgumentException('请求的区域不存在！');
        }
        $this->spaces = ParkSpace::query()
            ->where('park_area_id', '=', $parkAreaId)
            ->get();
        if ($id) {
            $this->spaces = $this->spaces->filter(function ($item) use ($id) {
                return $item->id != $id;
            });
        }
    }

    /**
     * 一键添加
     * @param array $data
     * @throws InvalidArgumentException
     */
    public function autoStore(array $data) {
        $this->init($data['park_area_id']);
        $types = array_count_values($this->spaces->pluck('type')->toArray());
        $categories = array_count_values($this->spaces->pluck('category')->toArray());
        $fixed = $types[ParkSpace::TYPE_FIXED] ?? 0;
        $charging = $categories[ParkSpace::CATEGORY_CHARGING_PILE] ?? 0;
        $spaces = array();
        for ($i = $this->spaces->count() + 1; $i <= $this->area->parking_places_count; $i++) {
            $spaces[] = [
                'number' => $this->area->code.$i,
                'type' => $fixed++ < $this->area->fixed_parking_places_count ? ParkSpace::TYPE_FIXED : ParkSpace::TYPE_TEMP,
                'category' => $charging++ < $this->area->charging_pile_parking_places_count ? ParkSpace::CATEGORY_CHARGING_PILE : ParkSpace::CATEGORY_ORDINARY,
                'status' => ParkSpace::STATUS_UNPUBLISHED,
                'remark' => '一键添加',
                'park_id' => $this->area->park_id
            ];
        }
        $this->area->spaces()->createMany($spaces);
    }

    /**
     * 车位编号
     * @param ParkSpace $space
     * @throws InvalidArgumentException
     */
    private function number(ParkSpace $space) {
        if ($space->isClean('number')) {
            return;
        }
        $records = $this->spaces->pluck('number')->toArray();
        if (in_array($space->number, $records)) {
            throw new InvalidArgumentException('该编号已存在');
        }
    }

    /**
     * 车位类型
     * @param ParkSpace $space
     * @throws InvalidArgumentException
     */
    private function type(ParkSpace $space) {
        if ($space->isClean('type')) {
            return;
        }
        $records = array_count_values($this->spaces->pluck('type')->toArray());
        $fixed = $records[ParkSpace::TYPE_FIXED] ?? 0;
        $temp = $records[ParkSpace::TYPE_TEMP] ?? 0;
        if ($fixed > $this->area->fixed_parking_places_count) {
            throw new InvalidArgumentException('该区域固定车位已满');
        }
        if ($temp > $this->area->temp_parking_places_count) {
            throw new InvalidArgumentException('该区域临停车位已满');
        }
    }

    /**
     * 车位类别
     * @param ParkSpace $space
     * @throws InvalidArgumentException
     */
    private function category(ParkSpace $space) {
        if ($space->isClean('category')) {
            return;
        }
        $records = array_count_values($this->spaces->pluck('category')->toArray());
        $ordinary = $records[ParkSpace::CATEGORY_ORDINARY] ?? 0;
        $charging = $records[ParkSpace::CATEGORY_CHARGING_PILE] ?? 0;
        if ($charging > $this->area->charging_pile_parking_places_count) {
            throw new InvalidArgumentException('该区域充电桩车位已满');
        }
        if ($ordinary > $this->area->parking_places_count - $this->area->charging_pile_parking_places_count) {
            throw new InvalidArgumentException('该区域普通车位已满');
        }
    }
}
