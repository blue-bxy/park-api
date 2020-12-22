<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Users\ParkingSpaceRentalBill;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageRentalOrder extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query=UserOrder::query();

        $query->whereHas('carRent',function ($query){
            $query->where('rent_type_id',2);
        });

        $query->with('carStop','carApts','carApts.carRent');

        return $query->search($this->request)->orderBy('id','desc');
    }

    public function headings(): array
    {
        return ['订单号','唯一标识号','结算类型','预约车牌号','预约时长','预约金额','停车时长','停车金额'];
    }

    public function map($row): array
    {
        switch ($row->status){
            case 'pending':
                $status = '待支付';
                break;
            case 'paid':
                $status = '已支付';
                break;
            case 'cancelled':
                $status = '已取消';
                break;
            case 'failed':
                $status = '已失败';
                break;
            case 'refunded':
                $status = '已退款';
                break;
            case 'finished':
                $status = '已完成';
                break;
            case 'commented':
                $status = '已评价';
                break;
        }
        return [
            $row->order_no,
            $row->carRent->rent_no ?? null,
            $status,
            $row->carApts->userCar->car_number ?? null,
            $row->carApts->apt_time ?? null,
            $row->formatAmount($row->carApts->deduct_amount) ?? null,
            $row->carStop->stop_time ?? null,
            $row->formatAmount($row->amount) ?? null,
        ];
    }
}
