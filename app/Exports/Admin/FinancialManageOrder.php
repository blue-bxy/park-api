<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\Orders;
use App\Models\Users\UserOrder;
use App\Models\Users\UserRefund;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageOrder extends ExcelExport implements FromQuery, WithHeadings, WithMapping
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
        if($this->status=='refunded'){
            return UserRefund::query()->with('order')->search($this->request);
        }
        return UserOrder::query()
            ->where('status',$this->status)
            ->with('parks')
            ->search($this->request);
    }

    public function headings(): array
    {
        if ($this->status == 'refunded') {
            return ['退款订单号','来源订单号','车牌号','出场时间','停车金额','停车场名称','退款金额','退款时间','退款原因','备注'];
        }
        return ['结算订单号','订单类型','结算类型','发布类型','车牌号','金额','结算时间','支付方式','电子支付第三方（元）'];
    }

    public function map($row): array
    {
        if ($this->status=='refunded') {
            return [
                $row->refund_no,
                $row->order->order_no,
                $row->order->car_num,
                $row->order->carStop->car_out_time??null,
                $row->order->actual_price,
                $row->order->parks->project_name??null,
                $row->refunded_amount,
                $row->refunded_at?$row->refunded_at:$row->failed_at,
                $row->reason,
                $row->remarks
            ];
        }
        return [
            $row->order_no,
            $row->type,
            $row->status,
            $row->publish_type,
            $row->car_num,
            $row->actual_price,
            $row->payed_at,
            $row->payment_method,
            $row->third_payment,
        ];

    }
}
