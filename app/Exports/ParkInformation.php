<?php

namespace App\Exports;


use App\Models\Parks\Park;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkInformation extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Park::query()
            ->with('parkStall','projectGroup','parkService','property')
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['序号','停车场名称','停车场编号','公司名称','集团名称','所属城市','车位总数','固定车位','激活码','运营状态','属性','车场状态'];
    }

    public function map($row): array
    {

        return [
            $row->id,
            $row->project_name,
            $row->park_number,
            $row->company,
            $row->projectGroup->group_name,
            $row->park_city == '市辖区' ? $row->park_province : $row->park_province.$row->park_city,
            optional($row->parkStall)->carport_count,
            optional($row->parkStall)->fixed_carport_count,
            optional($row->parkService)->activation_code,
            Park::OPERATION_STATES[$row->park_operation_state] ?? null,
            Park::PROPERTIES[$row->park_property] ?? null,
            Park::STATES[$row->park_state] ?? null,
        ];

    }
}


