<?php

namespace App\Exports;


use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DmangerCarRent extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
//        $query = CarApt::query();
//
//        $query->select('id','park_id','user_order_id','car_rent_id','user_car_id');
//
//        $query->with(['parks','carRent','userOrder','userCar']);
//
//        return $query->rentSearch($this->request);
        $query = CarRent::query();

        $query->with('parks', 'orders');

        return $query->search($this->request)->orderBy('id','desc');

    }

    public function headings(): array
    {

        return ['用户名','唯一标识号','停车场','发布时间','停止时间','出租时间段','出租时长','车位编号','出租单价','出租实收金额','出租车位状态'];
    }
    public function map($row): array
    {

        if($row->rent_status == 1){
            $row->rent_status = '启用';
        }else{
            $row->rent_status = '停用';
        }

        return[
            $row->user->nickname ?? null,
            $row->rent_no,
            $row->parks->project_name ?? null,
            $row->rent_start_time,
            $row->rent_end_time,
            $row->start . '-' . $row->stop,
            $row->rent_time,
            $row->rent_num,
            $row->formatAmount($row->rent_price),
            $row->orders->sum('total_amount') ?? null,
            $row->rent_status,
        ];
    }
}
