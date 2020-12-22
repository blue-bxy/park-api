<?php

namespace App\Imports\Admin;

use App\Models\Brand;
use App\Models\Parks\ParkDevice;
use App\Models\Parks\ParkSpace;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

abstract class ParkDeviceImport implements WithHeadingRow
{
    protected $device;

    /**
     * 设备编号记录
     * @var array
     */
    protected $numbers;

    /**
     * 品牌（带型号）列表
     * @var array
     */
    protected $brands;

    /**
     * 车位列表
     * @var array
     */
    protected $spaces;

    public function __construct(ParkDevice $lock) {
        $this->device = $lock;
    }

    protected function init(int $parkId) {
        $this->numbers = $this->device->pluck('number')->toArray();
        $this->brands = Brand::query()
            ->with('models:id,brand_id,name')
            ->select(['id', 'name'])
            ->get()->toArray();
        $this->spaces = ParkSpace::query()
            ->where('park_id', '=', $parkId)
            ->select(['id', 'number'])
            ->get()->toArray();
    }

    /**
     * 保存数据
     * @param array $data
     * @param array $extra
     * @return array
     * @throws \Throwable
     */
    public function save(array $data, array $extra) {
        $data = $this->map($data);
        if (!empty($data)) {
            $this->init($extra['park_id']);
        }
        $devices = $this->check($data);
        foreach ($devices as $device) {
            $device = array_merge($device, $extra);
            if (!$device['error']) {
                DB::transaction(function () use ($device) {
                    $this->device = $this->device->create($device);
                    $spaces = array();
                    foreach ($device['spaces'] as $space) {
                        //筛选车位编号存在的情况（id！=0）
                        if ($space['id']) {
                            $spaces[] = [
                                'park_space_id' => $space['id'],
                                'device_type' => get_class($this->device),
                                'device_id' => $this->device->id
                            ];
                        }
                    }
                    DB::table('park_space_has_devices')->insert($spaces);
                });
            }
        }
        return $devices;
    }

    /**
     * 转换键名为表字段名
     * @param array $data
     * @return array
     */
    abstract protected function map(array $data): array;

    /**
     * 数据检查
     * @param array $devices
     * @return array
     */
    protected function check(array $devices) {
        foreach ($devices as &$device) {
            if (in_array($device['number'], $this->numbers)) {
                $device['error'] = true;
                $device['msg'] = '该编号已存在';
            } else {
                $this->numbers[] = $device['number'];
                $this->brand($device);
            }
            $this->space($device);
        }
        return $devices;
    }

    /**
     * 品牌、型号检查
     * @param array $device
     */
    protected function brand(array &$device) {
        $device['brand_id'] = 0;
        $device['brand_model_id'] = 0;
        foreach ($this->brands as $brand) {
            if ($device['brand_name'] == $brand['name']) {
                $device['brand_id'] = $brand['id'];
                foreach ($brand['models'] as $model) {
                    if ($device['model_name'] == $model['name']) {
                        $device['brand_model_id'] = $model['id'];
                        break;
                    }
                }
            }
        }
        if ($device['brand_model_id']) {
            $device['error'] = false;
            $device['msg'] = '成功';
        } else {
            $device['error'] = true;
            $device['msg'] = '该型号还未注册';
        }
    }

    /**
     * 车位检查（暂未考虑重复绑定的情况）
     * @param array $device
     */
    protected function space(array &$device) {
        $spaces = array();
        foreach ($device['spaces'] as $number) {
            $exist = false;
            foreach ($this->spaces as $space) {
                //车位编号存在
                if ($number == $space['number']) {
                    $spaces[] = [
                        'id' => $space['id'],
                        'number' => $number
                    ];
                    $exist = true;
                    break;
                }
            }
            //车位编号不存在
            if (!$exist) {
                $spaces[] = [
                    'id' => 0,
                    'number' => $number
                ];
            }
        }
        $device['spaces'] = $spaces;
    }

}
