<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\Record;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageRecord extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Record::with('withdrawal');
    }

    public function headings(): array
    {
        return ['来源订单号','申请金额','车场名称','调整单号','调整类型','调整金额','调整原因','公司是否亏损','操作人员','操作时间'];
    }

    public function map($row): array
    {
        return [
            $row->withdrawal->withdrawal_no ?? null,
            $row->withdrawal->apply_money ?? null,
            $row->withdrawal->park->project_name ?? null,
            $row->record_no,
            $row->adjust_type,
            $row->adjust_amount,
            $row->reason,
            $row->is_loss,
            $row->operator,
            $row->created_at,
        ];

    }
}
