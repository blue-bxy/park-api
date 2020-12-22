<?php

namespace App\Exports\Admin;

use App\Models\Parks\ParkBluetooth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkBluetoothExport extends ParkDevicesExport implements FromArray, WithHeadings, WithMapping
{
    public function array(): array {
        $bluetooths = ParkBluetooth::query()
            ->with(['brand', 'model', 'spaces', 'park', 'area'])
            ->search($this->request)
            ->get();
        $data = array();
        foreach ($bluetooths as $bluetooth) {
            $data[] = $bluetooth->toArray();
            $data[] = [
                'brand' => '车位编号'
            ];
            foreach ($bluetooth->spaces as $space) {
                $data[] = [
                    'brand' => $space->number
                ];
            }
        }
        return $data;
    }
    /**
     * 表头
     * @return array|string[]
     */
    public function headings(): array {
        return ['蓝牙编号', '品牌', '型号', 'ip地址', '通信协议', '网关', '停车场名称', '区域', '网络状态'];
    }

    /**
     * 导出数据
     * @param mixed $row
     * @return array
     */
    public function map($row): array {
        //输出待处理
        return [
            $row['number'] ?? null,
            $row['brand']['name'] ?? $row['brand'],
            $row['model']['name'] ?? null,
            $row['ip'] ?? null,
            $row['protocol'] ?? null,
            $row['gateway'] ?? null,
            $row['park']['project_name'] ?? null,
            $row['area']['name'] ?? null,
            isset($row['network_status']) ? (ParkBluetooth::NETWORK_STATUSES[$row['network_status']] ?? null) : null,
        ];
    }
}
