<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\ParkingFee;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageParkEarnings extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $start_time;
    protected $end_time;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $time=$request->input('time');
        $start_time=Carbon::parse($time)->startOfDay();
        $end_time=Carbon::parse($time)->endOfDay();
        $this->start_time=$start_time;
        $this->end_time=$end_time;
    }

    public function query()
    {
        return UserOrder::query()
            ->search($this->request)
            ->selectRaw('sum(total_amount) as income,park_id,finished_at')
            ->whereBetween('finished_at',[$this->start_time,$this->end_time])
            ->groupBy('park_id');
    }

    public function headings(): array
    {
        return ['账单日期','停车场名称','结算金额','手续费金额'];
    }

    public function map($row): array
    {
        $park_fee=ParkingFee::where('park_id',$row->park_id)->pluck('fee')[0]??null;
        $income=number_format($row->income / 100, 2);
        $fee=$income*($park_fee/100);
        return [
            $row->finished_at->format('Y-m-d'),
            $row->parks->project_name??null,
            $income,
            $fee,
        ];
    }
}
