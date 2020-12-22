<?php

namespace App\Exports;

use App\Models\Dmanger\CarStop;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DmangerCarStop extends ExcelExport implements FromQuery, WithHeadings ,WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = CarStop::query();

        $query->with(['park','userOrder','userOrder.carApts.aptOrder','userCar']);

        $query->select('id','park_id','user_car_id','user_order_id','car_in_time','car_out_time','stop_price','car_in_img','car_out_img','special_price','washed_price');

        return $query->search($this->request)->orderBy('id','desc');
    }

    public function headings(): array
    {

        return ['停车场名称','车牌号','入场时间','出场时间','应收金额','优免金额','特殊处理损失','被冲车辆损失'];
    }
    public function map($row): array
    {
        return[
            $row->park->project_name ?? null,
            $row->car_num ? $row->car_num : ($row->userCar->car_number ?? null),
            $row->car_in_time,
            $row->car_out_time,
            $row->userOrder->amount ?? null,
            $row->userOrder->discount_amount ?? null,
            $row->special_price,
            $row->washed_price,
        ];
    }
}
