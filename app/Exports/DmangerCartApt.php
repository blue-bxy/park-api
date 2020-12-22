<?php

namespace App\Exports;

use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\Park;
use App\Models\Users\UserCar;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DmangerCartApt extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {

        $query = CarApt::query();

        $query->with('orders', 'user', 'userCar', 'parks', 'carRent','parkSpace','divide');

        $query->addSelect([
            'order_no' => UserOrder::select('order_no')
                ->limit(1)
                ->whereColumn('user_orders.id', 'car_apts.user_order_id')
        ]);

        return $query->search($this->request)->orderBy('id','desc');
    }

    public function headings(): array
    {

        return ['停车场名称','车位号','订单号','交易单号','预约支付费用','预约实际扣款','平台手续费','预约支付时间','车牌号','入场时间','预约退返金额'];
    }
    public function map($row): array
    {
        return[
            $row->parks->project_name,
            $row->parkSpace->number ?? null,
            $row->order_no,
            $row->orders->map->transaction_id,
            $row->formatAmount($row->total_amount),
            $row->formatAmount($row->deduct_amount),    // 预约实际扣款
            $row->formatAmount($row->divide->platform_fee) ?? 0,    // 平台手续费
            ($order = $row->orders->first()) ? $order->paid_at->format('Y-m-d H:i') : null,
            $row->userCar->car_number ?? null,
            $row->userOrder ? ($row->userOrder->carStop ? $row->userOrder->carStop->car_in_time->format('Y-m-d H:i'): null) : null,
            $row->formatAmount($row->refund_total_amount)
        ];
    }
}
