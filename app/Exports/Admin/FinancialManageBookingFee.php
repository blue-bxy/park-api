<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\BookingFee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageBookingFee extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function query()
    {
        return BookingFee::query()->with('park','user')->orderBy('id','desc');
    }

    public function headings(): array
    {
        return ['车场名称','设置人员','设置时间','状态'];
    }

    public function map($row): array
    {

        $status = [1 => '停用',2 => '启用'];

        return [
            $row->park->project_name,
            $row->user->name,
            $status[$this->status],
            $row->updated_at->format('Y-m-d H:i')
        ];

    }
}
