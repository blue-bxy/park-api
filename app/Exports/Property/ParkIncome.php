<?php

namespace App\Exports\Property;


use App\Exports\ExcelExport;
use App\Http\Resources\Property\ParkIncomeResource;
use App\Models\Parks\Park;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkIncome extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        // 满足查询条件进行查询
        $query = UserOrder::query();

        $query->with(['parks','carStop','carApts','carApts.orders','car','coupon']);

        $admin_id = ($this->request ->user())->id;

        $park = Park::where('property_id',$admin_id)->first();

        $park_id = $park->id;

        $query->where('park_id',$park_id);

        return $query->search($this->request );

    }

    public function headings(): array
    {

        return ['入场时间','出场时间','车牌号','车辆类型','预约时间','预约支付时间','优免券','优免金额','支付方式','支付金额','收费时间'];
    }
    public function map($row): array
    {
        $arr = [1=>'预约停车',2=>'非预约停车'];
        return[
//            $row->id,
//            $row->parks->project_name ?? null,
//            $row->order_no,
            $row->carStop->car_in_time->format('Y-m-d H:i') ?? null,
            $row->carStop->car_out_time->format('Y-m-d H:i') ?? null,
            $row->car->car_number ?? null,
            $arr[$row->order_type],
            $row->carApts->orders->map->created_at ?? null,
            $row->carApts->orders->map->paid_at ?? null,
            $row->coupon->title ?? null,
            $row->coupon->used_amount ?? null,
            \GuzzleHttp\json_encode($row->carApts->orders->map->payment_gateway,JSON_UNESCAPED_UNICODE),
            $row->total_amount,
            $row->paid_at->format('Y-m-d H:i:s'),
//            $row->subscribe_amount,
//            row->refund_amount,
//            $row->carStop->stop_time ?? null,
//            $row->amount,
//            $row->discount_amount,
//            $row->created_at->format('Y-m-d H:m:s'),
//            $row->carStop->special_price ?? null,
//            $row->carStop->washed_price ?? null,
        ];
    }
}
