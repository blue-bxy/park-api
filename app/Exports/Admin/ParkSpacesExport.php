<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Parks\ParkSpace;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkSpacesExport extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * 查询条件
     * @return \Illuminate\Database\Query\Builder
     */
    public function query() {
        return ParkSpace::query()->with('area')->search($this->request);
    }

    /**
     * 表头
     * @return array|string[]
     */
    public function headings(): array {
        return [
            '区域名称', '车位编号', '车位类型', '车位状态', '车位类别', '备注'
        ];
    }

    /**
     * 导出数据
     * @param mixed $row
     * @return array
     */
    public function map($row): array {
        return [
            $row->area->name ?? null,
            $row->number,
            ParkSpace::TYPES[$row->type] ?? null,
            ParkSpace::STATUSES[$row->status] ?? null,
            ParkSpace::CATEGORIES[$row->category] ?? null,
            $row->remark
        ];
    }

}
