<?php


namespace App\Services;


use App\Exceptions\InvalidArgumentException;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkStall;
use Illuminate\Database\Eloquent\Collection;

class ParkSpaceStatisticsService {
    /**
     * 设置车场时与区域的冲突
     * @param ParkStall $stall
     * @throws InvalidArgumentException
     */
    public function stallHasAreas(ParkStall $stall) {
        $areas = ParkArea::query()->where('park_id', '=', $stall->park_id)->get();
        $this->checkBetweenStallWithAreas($stall, $areas);
    }

    /**
     * 设置区域时与车场的冲突
     * @param ParkArea $area
     * @throws InvalidArgumentException
     */
    public function areaBelongToStall(ParkArea $area) {
        $stall = ParkStall::query()->where('park_id', '=', $area->park_id)->first();
        if (empty($stall)) {
            throw new InvalidArgumentException();
        }
        $areas = ParkArea::query()
            ->where('park_id', '=', $area->park_id);
        if ($area->id) {
            $areas->where('id', '<>', $area->id);
        }
        $areas = $areas->get();
        $areas->push($area);
        $this->checkBetweenStallWithAreas($stall, $areas);
    }

    /**
     * 设置区域时与车位的冲突
     * @param ParkArea $area
     * @throws InvalidArgumentException
     */
    public function areaHasSpaces(ParkArea $area) {
        $spaces = ParkSpace::query()->where('park_area_id', '=', $area->id)->get();
        if ($area->parking_places_count < count($spaces)) {
            throw new InvalidArgumentException('总车位数低于已存在的车位数量！');
        }
        $tempQuantity = 0;  //临时车位数
        $fixedQuantity = 0; //固定车位数
        $chargedQuantity = 0;   //充电桩车位数
        foreach ($spaces as $space) {
            if ($space->type  == ParkSpace::TYPE_FIXED) {
                $fixedQuantity++;
            } elseif ($space->type == ParkSpace::TYPE_TEMP) {
                $tempQuantity++;
            }
            if ($space->category == ParkSpace::CATEGORY_CHARGING_PILE) {
                $chargedQuantity++;
            }
        }
        if ($area->temp_parking_places_count < $tempQuantity) {
            throw new InvalidArgumentException('临时车位数低于已存在的车位数量！');
        }
        if ($area->fixed_parking_places_count < $fixedQuantity) {
            throw new InvalidArgumentException('固定车位数低于已存在的车位数量！');
        }
        if ($area->charging_pile_parking_places_count < $chargedQuantity) {
            throw new InvalidArgumentException('充电桩车位数低于已存在的车位数量！');
        }
    }

    /**
     * @param ParkStall $stall
     * @param Collection $areas
     * @throws InvalidArgumentException
     */
    private function checkBetweenStallWithAreas(ParkStall $stall, Collection $areas) {
        $quantity = 0;  //总车位数
        $tempQuantity = 0;  //临时车位数
        $fixedQuantity = 0; //固定车位数
        $chargedQuantity = 0;   //充电桩车位数
        foreach ($areas as $area) {
            $quantity += $area->parking_places_count;
            $tempQuantity += $area->temp_parking_places_count;
            $fixedQuantity += $area->fixed_parking_places_count;
            $chargedQuantity += $area->charging_pile_parking_places_count;
        }
        if ($stall->carport_count < $quantity) {
            throw new InvalidArgumentException('总车位数超出该车场的设定值！');
        }
        if ($stall->temporary_carport_count < $tempQuantity) {
            throw new InvalidArgumentException('临时车位数超出该车场的设定值！');
        }
        if ($stall->fixed_carport_count < $fixedQuantity) {
            throw new InvalidArgumentException('固定车位数超出该车场的设定值！');
        }
        if ($stall->charging_pile_carport < $chargedQuantity) {
            throw new InvalidArgumentException('充电桩车位数超出该车场的设定值！');
        }
    }
}
