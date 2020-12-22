<?php

namespace App\Exports\Property;


use App\Exports\ExcelExport;
use App\Models\Parks\Park;
use App\Models\Property;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CarRent extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = \App\Models\Dmanger\CarRent::query();

        $query->with('parks', 'orders','user','rentals');

        $admin_id = ($this->request->user())->id;

        $park_id = Property::where('id',$admin_id)->value('park_id');

        $query->where('park_id',$park_id);

        return $query->search($this->request)->orderBy('id','desc');
    }

    public function headings(): array
    {

        return ['用户名','唯一标识号','出租时间段','出租时长','车位编号','出租单价','出租实收金额'];
    }
    public function map($row): array
    {

        $apt = ($row->apt ? $row->apt->sum('owner_fee') : null);

        $stop = $row->carApt? ($row->carApt->map->userOrder ? ($row->carApt->map->userOrder->map->divide->sum('owner_fee')):null): null;

        $amount = $apt + $stop;

        return [
            $row->user->nickname ?? null,
            $row->rent_no,
            $row->start . '-' . $row->stop,
            $row->carApt->sum('apt_time'),
            $row->rent_num,
            $row->rent_price,
            $amount,
        ];
    }
}
