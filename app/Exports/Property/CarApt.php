<?php

namespace App\Exports\Property;


use App\Exports\ExcelExport;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Parks\Park;
use App\Models\Property;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CarApt extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        // 查询
        $query = \App\Models\Dmanger\CarApt::query();

        $query->with('orders', 'parkSpace.spaceType','user','userCar', 'parks', 'carRent');

        $query->addSelect([
            'order_no' => UserOrder::select('order_no')
                ->limit(1)
                ->whereColumn('user_orders.id', 'car_apts.user_order_id')
        ]);

        $admin_id = (($this->request)->user())->id;

        $park_id = Property::where('id',$admin_id)->value('park_id');

        $query->where('park_id',$park_id);

        return $query->search($this->request)->orderBy('id','desc');
    }

    public function headings(): array
    {

        return ['入场时预支付','预支付时间','车位号','车牌号','手机号','预约实际扣款','车场实收金额','实际入场时间','预约退款金额','订单号','交易单号','发布类型','状态'];
    }
    public function map($row): array
    {
        if($row->userOrder->status == 'pending'){
            $status = '待支付';
        }elseif ($row->userOrder->status == 'paid'){
            $status = '已支付';
        }elseif ($row->userOrder->status == 'cancelled'){
            $status = '已取消';
        }elseif ($row->userOrder->status == 'failed'){
            $status = '已失败';
        }elseif ($row->userOrder->status == 'refunded'){
            $status = '已退款';
        }elseif ($row->userOrder->status == 'finished'){
            $status = '已完成';
        }else{
            $status = '已评价';
        }

        $publisher_type = [1=>'物业',2=>'业主',3=>'云端'];

        return[
            $row->formatAmount($row->total_amount),
            // 入场支付时间
            ($order = $row->orders->first()) ? $order->paid_at->format('Y-m-d H:i') : null,
            $row->parkSpace->number ?? null,
            $row->userCar->car_number ?? null,
            $row->user->mobile,
            $row->formatAmount($row->deduct_amount),    // 预约实际扣款
            $row->divide->park_fee ?? 0,    // 车场分成
            $row->userOrder ? ($row->userOrder->carStop ? $row->userOrder->carStop->car_in_time->format('Y-m-d H:i'): null) : null,
            $row->formatAmount($row->refund_total_amount),  //  退款费用
            $row->order_no,
            $row->orders->map->transaction_id,
            $publisher_type[$row->carRent->rent_type_id ?? 1],
//            $row->apt_start_time . '-' .  $row->car_in_time,
//            $row->apt_time,
            $status
        ];
    }
}
