<?php

namespace App\Exports\Property;


use App\Exports\ExcelExport;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Parks\Park;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Property;

class CarStop extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = \App\Models\Dmanger\CarStop::query();

        $query->with(['park','userOrder','userOrder.carApts.aptOrder','userCar']);

        $admin_id = (($this->request)->user())->id;

        $park_id = Property::where('id',$admin_id)->value('park_id');

        $query->where('park_id',$park_id);

        return $query->search($this->request)->orderBy('id','desc');
    }

    public function headings(): array
    {

        return ['入场时间','出场时间','车牌号','车辆类型','优免金额','支付方式','支付金额','收费时间'];
    }

    public function map($row): array
    {
        switch($row->userOrder->payment_gateway ?? null){
            case 'wx_app':
                $row->userOrder->payment_gateway = '微信';
                break;
            case 'ali_app':
                $row->userOrder->payment_gateway = '支付宝';
                break;
            case 'balance':
                $row->userOrder->payment_gateway = '支付宝';
                break;
            default:
                null;
        }

        if($row->userOrder->carApts ?? null){
            $type = '预约停车';
        }else{
            $type = '非预约停车';
        }

        if($row->car_in_time){
            $car_in_time = $row->car_in_time->format('Y-m-d H:i');
        }else{
            $car_in_time = null;
        }

        if($row->car_out_time){
            $car_out_time = $row->car_out_time->format('Y-m-d H:i');
        }else{
            $car_out_time = null;
        }

        return [
            $car_in_time,
            $car_out_time,
            $row->car_num ? $row->car_num : ($row->userCar->car_number ?? null ),
            $type,
            $row->formatAmount($row->userOrder->discount_amount ?? 0),
            $row->userOrder->payment_gateway ?? null,
            $row->formatAmount($row->userOrder->amount ?? 0),
            $row->userOrder ? $row->userOrder->paid_at->format('Y-m-d H:i'):null,
        ];
    }
}
