<?php

namespace App\Exports;

use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * 车场收入的报表
 * @package App\Exports
 */
class DmangerParkIncome extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = UserOrder::query();

        $query ->with(['parks','carStop']);

        // $query ->selectRaw("id,order_no,park_id,car_stop_id,user_car_id,created_at,subscribe_amount,refund_amount,amount,discount_amount,car_apt_id,payment_gateway");

        return $query->search($this->request);
    }

    public function headings(): array
    {

        return ['车场名称','订单号','车牌号','订单创建时间','预约时长','预约应付金额', '预约退款金额','预约优免金额','预约支付方式',
        '出场时间','停车时长','停车费用','停车支付的金额','停车优免金额','停车订单支付状态','停车费支付方式','操作说明'];
    }

    public function map($row): array
    {
        switch($row->payment_gateway){
            case 'wx_app':
                $row->payment_gateway = '微信';
                break;
            case 'ali_app':
                $row->payment_gateway = '支付宝';
                break;
            default:
                $row->payment_gateway = '余额';
        }
        return[
            $row->parks->project_name ?? null,
            $row->order_no,
            $row->car ? $row->car->car_number : $row->car_num,
            $row->carApts ? $row->carApts->apt_start_time->format('Y-m-d H:i') : null,
            $row->carApts->apt_time ?? null,
            $row->formatAmount($row->subscribe_amount),
            $row->formatAmount($row->refund_amount),
            $row->formatAmount($row->discount_amount),
            $row->payment_gateway,
            $row->carStop ? ($row->carStop->car_out_time ? $row->carStop->car_out_time->format('Y-m-d H:i') : null) : null,
            $row->carStop->stop_time ?? null,
            $row->formatAmount($row->amount),
            $row->formatAmount($row->parking_fee),        // app显示需支付的停车金额
            $row->formatAmount($row->carStop->discount_amount) ?? null,  // 停车优免金额
            $row->formatAmount($row->carStop->order->status) ?? null,   // 停车支付状态
            $row->carStop->order->payment_gateway ?? null,    // 停车的支付方式
            $row->explain,    // 操作说明
        ];

    }
}
