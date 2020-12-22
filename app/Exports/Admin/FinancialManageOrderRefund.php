<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Users\UserRefund;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageOrderRefund extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return UserRefund::query()
            ->with('order')
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['退款单号','来源订单号','交易号','车牌号','预约时间','预约金额','停车场名称',
            '退款金额','退款时间','退款原因','备注'];
    }

    public function map($row): array
    {
        return [
            $row->refund_no,
            $row->order->no??null,
            $row->order->transaction_id??null,
            $row->order->carApt->car->car_number??null,
            $row->order->created_at->format('Y-m-d H:m:s')??null,
            $row->order->amount??null,
            $row->order->carApt->parks->project_name??null,
            $row->refunded_amount??null,
            $row->refunded_at,
            $row->reason,
            $row->remarks,
        ];

    }
}
