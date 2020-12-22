<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\Withdrawal;
use App\Models\User;
use App\Models\Users\UserOrder;
use App\Models\Users\UserRefund;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageWithdrawal extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->status = $request->input('status');
    }

    public function query()
    {
        return Withdrawal::query()
            ->with('park', 'user', 'reviewer')
            ->search($this->request);
    }

    public function headings(): array
    {
        if ($this->status == 1) {
            return ['提现单号','申请时间','申请金额','申请人','停车场名称'];

        }elseif ($this->status==2){
            return ['提现单号','申请时间','申请金额','停车场名称','审核人','审核时间'];
        }elseif ($this->status==3){
            return ['提现单号','申请时间','申请金额','申请人','项目名称','审核人','审核时间','完成时间','备注'];
        }
        return ['提现单号','申请时间','申请金额','申请人','停车场名称','状态','完成时间'];
    }

    public function map($row): array
    {
        if ($this->status==1) {
                return [
                    $row->withdrawal_no,
                    $row->apply_time,
                    $row->apply_money,
                    $row->user instanceof User ? $row->user->nickname : $row->user->name,
                    $row->park->project_name??null,
                ];
        }elseif ($this->status==2){
            return [
                $row->withdrawal_no,
                $row->apply_time,
                $row->apply_money,
                $row->park->project_name??null,
                $row->reviewer->name??null,
                $row->audit_time
            ];
        }elseif ($this->status==3){
            return [
                $row->withdrawal_no,
                $row->apply_time,
                $row->apply_money,
                $row->user instanceof User ? $row->user->nickname : $row->user->name,
                $row->park->project_name??null,
                $row->reviewer->name??null,
                $row->audit_time,
                $row->completion_time,
                $row->remark
            ];
        }
        return [
            $row->withdrawal_no,
            $row->apply_time,
            $row->apply_money,
            $row->user instanceof User ? $row->user->nickname : $row->user->name,
            $row->park->project_name??null,
            $row->status_rename,
            $row->completion_time
        ];
    }
}
