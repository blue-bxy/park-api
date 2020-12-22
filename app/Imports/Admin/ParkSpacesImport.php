<?php

namespace App\Imports\Admin;

use App\Exceptions\InvalidArgumentException;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkSpace;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ParkSpacesImport implements WithHeadingRow
{
    /**
     * 请求区域
     * @var ParkArea
     */
    private $area;

    /**
     * 车位记录数组
     * @var Collection
     */
    private $spaces;

    public function __construct(Request $request) {
        $this->area = ParkArea::query()->find($request->input('park_area_id'));
        if (empty($this->area)) {
            throw new InvalidArgumentException('请求的区域不存在！');
        }
        $this->spaces = ParkSpace::query()
            ->where('park_area_id', '=', $request->input('park_area_id'))
            ->get();
    }

    /**
     * 保存导入数据
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function save(array $data) {
        $spaces = $this->map($data);
        if ($this->check($spaces))  {
            $this->area->spaces()->createMany($spaces);
        }
        return $spaces;
    }

    /**
     * 导入数据映射
     * @param array $data
     * @return array
     */
    private function map(array $data) {
        $spaces = array();
        foreach ($data as $datum) {
            $spaces[] = [
                'rank' => $datum['序号'],
                'number' => $datum['车位编号'],
                'type' => $datum['车位类型'],
                'status' => ParkSpace::STATUS_UNPUBLISHED,
                'category' => $datum['车位类别'],
                'remark' => $datum['备注'],
                'park_id' => $this->area->park_id,
                'park_area_id' => $this->area->id,
                'error' => false,
                'msg' => null
            ];
        }
        return $spaces;
    }

    /**
     * 导入数据检查
     * @param array $data
     * @return boolean
     * @throws InvalidArgumentException
     */
    private function check(array &$data) {
        $this->quantity($data);
        $this->type($data);
        $this->category($data);
        $this->number($data);
        return !$this->hasError($data);
    }

    /**
     * 导入数量检查
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function quantity(array $data) {
        if ($this->area->parking_places_count <= $this->spaces->count()) {
            throw new InvalidArgumentException('该区域车位已满');
        }
        if ($this->area->parking_places_count <= $this->spaces->count() + count($data)) {
            throw new InvalidArgumentException('该文件导入的车位数已超过此区域最大容量，请确认后重新提交');
        }
    }

    /**
     * 车位类型检查
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function type(array &$data) {
        $records = $this->spaces->pluck('type')->toArray(); //现有记录的车位类型
        $records = array_count_values($records);
        $fixed = $records[ParkSpace::TYPE_FIXED] ?? 0;  //固定车位数量
        $temp = $records[ParkSpace::TYPE_TEMP] ?? 0;    //临停车位数量
        $types = array(ParkSpace::TYPE_FIXED, ParkSpace::TYPE_TEMP);
        foreach ($data as &$datum) {
            if (in_array($datum['type'], $types)) {
                if ($datum['type'] == ParkSpace::TYPE_FIXED) {
                    $fixed++;
                } else {
                    $temp++;
                }
            } else {
                $datum['error'] = true;
                $datum['msg'] = '车位类型错误 ';
            }
        }
        if ($fixed > $this->area->fixed_parking_places_count) {
            throw new InvalidArgumentException('该区域固定车位已满');
        }
        if ($temp > $this->area->temp_parking_places_count) {
            throw new InvalidArgumentException('该区域临停车位已满');
        }
    }

    /**
     * 车位类别检查
     * @param array $data
     * @throws InvalidArgumentException
     */
    private function category(array &$data) {
        $records = $this->spaces->pluck('category')->toArray(); //现有记录的车位类别
        $records = array_count_values($records);
        $ordinary = $records[ParkSpace::CATEGORY_ORDINARY] ?? 0;    //普通车位数量
        $charging = $records[ParkSpace::CATEGORY_CHARGING_PILE] ?? 0;   //充电桩车位数量
        $categories = array(ParkSpace::CATEGORY_ORDINARY, ParkSpace::CATEGORY_CHARGING_PILE);
        foreach ($data as &$datum) {
            if (in_array($datum['category'], $categories)) {
                if ($datum['category'] == ParkSpace::CATEGORY_ORDINARY) {
                    $ordinary++;
                } else {
                    $charging++;
                }
            } else {
                $datum['error'] = true;
                $datum['msg'] .= '车位类别错误 ';
            }
        }
        if ($charging > $this->area->charging_pile_parking_places_count) {
            throw new InvalidArgumentException('该区域充电桩车位已满');
        }
        if ($ordinary > $this->area->parking_places_count - $this->area->charging_pile_parking_places_count) {
            throw new InvalidArgumentException('该区域普通车位已满');
        }
    }

    /**
     * 车位编号检查
     * @param array $data
     */
    private function number(array &$data) {
        $records = $this->spaces->pluck('number')->toArray();
        foreach ($data as &$datum) {
            if (in_array($datum['number'], $records)) {
                $datum['error'] = true;
                $datum['msg'] .= '该编号已存在 ';
            }
        }
    }

    /**
     * 数据异常判断
     * @param array $data
     * @return bool
     */
    private function hasError(array &$data) {
        $hasError = false;
        foreach ($data as &$datum) {
            if ($datum['error']) {
                $hasError = true;
                break;
            }
            $datum['msg'] = '成功';
        }
        return $hasError;
    }
}
