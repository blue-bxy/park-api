<?php

namespace App\Exports\Admin;

use App\Models\Parks\ParkSpaceLock;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkSpaceLockExport extends ParkDevicesExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * 查询条件
     * @return \Illuminate\Database\Query\Builder
     */
    public function query() {
        return ParkSpaceLock::query()->with('space')->search($this->request);
    }

    /**
     * 表头
     * @return array|string[]
     */
    public function headings(): array {
        return ['地锁编号', '品牌', '型号', 'ip地址', '通信协议', '网关', '车位编号', '停车场名称', '区域', '网络状态'];
    }

    /**
     * 导出数据
     * @param mixed $row
     * @return array
     */
    public function map($row): array {
        return [
            $row->number,
            $row->brand->name,
            $row->model->name,
            $row->ip,
            $row->protocol,
            $row->gateway,
            $row->space->number ?? null,
            $row->park->project_name ?? null,
            $row->area->name ?? null,
            ParkSpaceLock::STATUSES[$row->network_status] ?? null,
        ];
    }
}
