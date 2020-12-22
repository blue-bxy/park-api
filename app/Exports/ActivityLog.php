<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\Property;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ActivityLog extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = \App\Models\ActivityLog::query();

        if ($time = $this->request->input('time')) {
            $query->where('created_at','>=',$time);
        }

        if ($user_name = $this->request->input('user_name')) {
            $query->whereHasMorph('causer', [Admin::class, Property::class], function ($query) use ($user_name) {
                $query->where("name", 'like', "%{$user_name}%");
            });
        }

        $query->with('causer');

        return $query->latest();
    }

    public function headings(): array
    {

        return ['用户','操作记录','登录IP','时间','操作'];
    }
    public function map($row): array
    {
        return[
            $row->causer?$row->causer->name:'default',
            $row->desc,
            $row->last_ip,
            $row->created_at->format('Y-m-d H:i:s')
        ];
    }
}
