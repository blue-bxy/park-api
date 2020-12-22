<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Bills\ParkBillSummary;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageParkBill extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;
    protected $state;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return ParkBillSummary::query()->with('park')->search($this->request);
    }

    public function headings(): array
    {
        return ['车场名称','交易类型','收入'];
    }

    public function map($row): array
    {
        return [
            $row->park->project_name,
            $row->bill_type_name,
            number_format($row->income / 100, 2),
        ];
    }
}
