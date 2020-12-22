<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\Orders;
use App\Models\Financial\ParkingFee;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageParkingFee extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->status = $request->input('status');
    }

    public function query()
    {
        return ParkingFee::query()
            ->with('park','admin')
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['停车场名称','结算费率','设置时间','设置人员'];
    }

    public function map($row): array
    {
        return [
            $row->park->project_name??null,
            $row->fee.'%',
            $row->created_at,
            $row->admin->name??null,
        ];

    }
}
