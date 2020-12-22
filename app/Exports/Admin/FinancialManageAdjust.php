<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\Record;
use App\Models\Financial\Withdrawal;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageAdjust extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Withdrawal::query()
            ->with('park', 'user')
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['提现单号','申请时间','申请金额','申请人','停车场名称','完成时间','状态'];
    }

    public function map($row): array
    {
        return [
            $row->withdrawal_no,
            $row->apply_time,
            $row->apply_money,
            $row->user instanceof User ? $row->user->nickname : $row->user->name,
            $row->park->project_name,
            $row->completion_time,
            $row->admin_id?'已审核':'未审核',
        ];
    }
}
