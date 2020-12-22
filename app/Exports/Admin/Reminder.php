<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Reminder extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = \App\Models\Financial\Reminder::query();

        $query->with(['user','park']);

        $query->search($this->request);

        return $query->latest();
    }

    public function headings(): array
    {
        return ['停车场','订单号','用户','车牌号','入场时间','出场时间','停车时长','停车金额','应付金额','逾期天数','催收状态','订单状态'];
    }

    public function map($row): array
    {
        switch ($row->state){
            case '1':
                $state = '未催收';
                break;
            case '2':
                $state = '推送通知';
                break;
            case '3':
                $state = '短信';
                break;
            case '4':
                $state = '人工催收';
                break;
        }

        if($row->pay_status == 'pending'){
            $status = '未支付';
        }

        if($row->pay_status == 'paid'){
            $status = '已支付';
        }

        return [
            $row->park->project_name ?? null,
            $row->order_no,
            $row->phone,
            $row->car_num,
            $row->car_in_time,
            $row->car_out_time,
            $row->stop_time,
            $row->formatAmount($row->amount),
            $row->formatAmount($row->deduct_amount),
            $row->days_overdue,
            $state,
            $status
        ];
    }
}
