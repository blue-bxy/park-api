<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Bills\PlatformBillSummary;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManagePlatformRecord extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function query()
    {
        return PlatformBillSummary::query()
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['账单日期','业务类型','收入','支出','余额'];
    }

    public function map($row): array
    {
        return [
            $row->date,
            $row->bill_type_name,
            $row->income,
            $row->expenses,
            $row->amount,
        ];

    }
}
