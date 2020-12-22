<?php

namespace App\Exports;

use App\Models\Parks\ParkingLotOpenApply;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkingLotOpenApplyExport extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return ParkingLotOpenApply::query()
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['停车场名称', '停车场地址', '出租人姓名', '出租人联系方式', '物业联系方式', '申请时间', '状态', '备注'];
    }

    public function map($row): array
    {
        return [
            $row->village_name,
            $row->address,
            $row->nickname,
            $row->telephone,
            $row->village_telephone,
            $row->created_at->format('Y-m-d H:i'),
            $row->status_rename,
            $row->remark,
        ];

    }
}
