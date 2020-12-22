<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Financial\SettleOrder;
use App\Models\Financial\SettleRefund;
use App\Models\Users\UserRefund;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageSettleRefund extends ExcelExport implements FromQuery, WithHeadings, WithMapping
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
        return ['退款单号','退款业务','交易金额','实退金额','退款状态','来源订单号','退款渠道','车场名称','退款类型','生成时间','退款原因','操作员'];
    }

    public function map($row): array
    {
        $no=$row->order instanceof CarAptOrder ? $row->order->no:null;
        $park=$row->order instanceof CarAptOrder ? $row->order->carApt->parks->project_name:null;
        return [
            $row->refund_no,
            '支付退款',
            $row->amount,
            $row->refunded_amount,
            $row->refunded_at?'已退款':'未退款',
            $no??null,
            $row->refund_channels,
            $park??null,
            $row->type,
            $row->created_at,
            $row->reason,
            $row->operator
        ];

    }
}
