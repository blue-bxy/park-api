<?php

namespace App\Exports\Admin;

use App\Models\Parks\ParkCamera;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkCameraExport extends ParkDevicesExport implements FromArray, WithHeadings, WithMapping
{
    public function array(): array {
        $cameras = ParkCamera::query()
            ->with(['brand', 'model', 'spaces', 'park', 'area'])
            ->search($this->request)
            ->get();
        $data = array();
        foreach ($cameras as $camera) {
            $data[] = $camera->toArray();
            $data[] = [
                'brand' => '车位编号'
            ];
            foreach ($camera->spaces as $space) {
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
        return ['摄像头编号', '品牌', '型号', 'ip地址', '通信协议', '网关', '停车场名称', '区域', '网络状态', '类型'];
    }

    /**
     * 导出数据
     * @param mixed $row
     * @return array
     */
    public function map($row): array {
        return [
            $row['number'] ?? null,
            $row['brand']['name'] ?? $row['brand'],
            $row['model']['name'] ?? null,
            $row['ip'] ?? null,
            $row['protocol'] ?? null,
            $row['gateway'] ?? null,
            $row['park']['project_name'] ?? null,
            $row['area']['name'] ?? null,
            isset($row['network_status']) ? (ParkCamera::NETWORK_STATUSES[$row['network_status']] ?? null) : null,
            isset($row['monitor_type']) ? (ParkCamera::MONITOR_TYPES[$row['monitor_type']] ?? null) : null
        ];
    }
}
