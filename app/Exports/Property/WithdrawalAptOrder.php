<?php

namespace App\Exports\Property;

use App\Exports\ExcelExport;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Financial\Withdrawal;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WithdrawalAptOrder extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $property=$this->request->user();
        $park_id=$property->park_id;
        $withdrawals = Withdrawal::where('park_id', $park_id)->orderBy('apply_time', 'desc')->get('apply_time');
        $end=$withdrawals[0]['apply_time'];
        $map=[];
        if(!empty($end)){
            $map[]=['created_at','>',$end];
        }else{
            $map[]=['created_at','<=',now()];
        }
        $query = CarAptOrder::query();
        $query->with(['carApt'=>function($query) use ($park_id){
            $query->where('park_id',$park_id);
        }]);
        $query->where($map);
        return $query->search($this->request);
    }

    public function headings(): array
    {
        return ['订单编号','车牌号','预约时长','预约费用','订单日期'];
    }

    public function map($row): array
    {
        return[
            $row->no,
            $row->carApt->car->car_number??null,
            $row->subscribe_time,
            $row->amount,
            $row->created_at->format('Y-m-d')
        ];
    }
}
