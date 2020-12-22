<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\ReminderRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReminderRecordExport extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = ReminderRecord::query();

        $query->with(['reminder','reminder.park','admin']);

        return $query->search($this->request)->latest();
    }

    public function headings(): array
    {
        return ['停车场','订单号','注册手机号','车牌号','入场时间','出场时间','停车时长','停车金额',
                '应付金额','逾期天数','催收状态','催收反馈','催收日期','操作人员'
        ];
    }

    public function map($row): array
    {
        switch ($row->state) {
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

        if($row->admin_id){
            $admin = $row->admin->name;
        }else{
            $admin = "系统";
        }

        return [
            $row->reminder->park->project_name ?? null,
            $row->reminder->order_no ?? null,
            $row->reminder->phone ?? null,
            $row->reminder->car_num ?? null,
            $row->reminder->car_in_time ?? null,
            $row->reminder->car_out_time ?? null,
            $row->reminder->stop_time ?? null,
            $row->formatAmount($row->reminder->amount) ?? null,
            $row->formatAmount($row->reminder->deduct_amount) ?? null,
            $row->reminder->days_overdue ?? null,
            $state,
            $row->feeedback,
            $row->created_at->format('Y-m-d H:i'),
            $admin
        ];
    }
}
