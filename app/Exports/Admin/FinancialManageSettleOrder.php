<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Financial\SettleOrder;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageSettleOrder extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return CarAptOrder::query()
            ->where('status','!=','refunded')
            ->with('carApt')
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['订单号','车牌号','入场时间','出场时间','预约时间','预约时长','预约费用','停车时长','停车费用','车场名称','订单状态'];
    }

    public function map($row): array
    {
        return [
            $row->no,
            $row->carApt->car->car_number??null,
            $row->carApt->carStop->car_in_time->format('Y-m-d H:m:s')??null,
            $row->carApt->carStop->car_out_time->format('Y-m-d H:m:s')??null,
            $row->created_at->format('Y-m-d H:m:s'),
            $row->subscribe_time,
            $row->amount,
            $row->carApt->carStop->stop_time??null,
            $row->carApt->carStop->stop_price??null,
            $row->carApt->parks->project_name??null,
            $row->status_rename,
        ];

    }
}
