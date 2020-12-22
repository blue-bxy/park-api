<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageAptOrder extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = UserOrder::query();
        return $query->search($this->request)->with(['carApts','carStop'])->orderBy('id','desc');
    }

    public function headings(): array
    {
        return ['结算订单号','结算类型','预约时长','预约金额','停车时长','停车金额'];
    }

    public function map($row): array
    {
        return [
            $row->order_no,
            $row->paid_at ? '正常' : '未支付',
            $row->carApts->apt_time ?? null,
            $row->formatAmount($row->carApts->total_amount) ?? '0',
            $row->carStop->stop_time ?? null,
            $row->formatAmount($row->amount),
        ];
    }
}
