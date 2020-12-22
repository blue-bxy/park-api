<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Payment;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageBadCredit extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Payment::query()
            ->with('user')
            ->where('status','failed')
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['订单号','手机号','订单金额','坏账金额','已补金额','是否需补缴',
            '坏账结果','坏账来源','创建时间','更新时间'];
    }

    public function map($row): array
    {
        return [
            $row->no,
            $row->user->mobile??null,
            $row->amount,
            $row->amount-$row->paid_amount,
            '',
            '',
            '',
            '',
            $row->created_at,
            $row->updated_at,
        ];
    }
}
