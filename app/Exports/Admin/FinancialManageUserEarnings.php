<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\ParkingFee;
use App\Models\User;
use App\Models\Users\ParkingSpaceRentalBill;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageUserEarnings extends ExcelExport implements FromQuery, WithHeadings, WithMapping
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
        return ParkingSpaceRentalBill::query()
            ->search($this->request)
            ->selectRaw('sum(rental_amount) as income,sum(fee) as fee, user_id,created_at')
            ->whereBetween('created_at',[$this->start_time,$this->end_time])
            ->where('order_type', UserOrder::class)
            ->groupBy('user_id');
    }

    public function headings(): array
    {
        return ['用户账号','结算金额','手续费金额','结算时间'];
    }

    public function map($row): array
    {
        return [
            User::where('id',$row->user_id)->pluck('nickname')[0],
            number_format($row->income / 100, 2),
            number_format($row->fee / 100, 2),
            $row->created_at->format('Y-m-d'),
        ];
    }
}
