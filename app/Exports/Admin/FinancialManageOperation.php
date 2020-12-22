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

class FinancialManageOperation extends ExcelExport implements FromQuery, WithHeadings, WithMapping
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
            ->with('park', 'user','reviewer')
            ->where('status','!=','3')
            ->search($this->request);
    }

    public function headings(): array
    {
        if($this->request->input('type')==2){
            return ['提现单号','提现手机号','提现人','提现金额','结算金额','业务类型','提现时间','完成时间','状态'];
        }
        return ['申请时间','提现单号','车场名称','申请金额','结算金额','提现时间','完成时间',
            '业务类型','是否冻结','状态','审核人','审核时间','提现人','提现人手机号'];
    }

    public function map($row): array
    {
        $adjust_amount=Record::where('withdrawal_id',$row->id)->orderBy('id','desc')->first('adjust_amount');
        if($this->request->input('type')==2){
            return [
                $row->withdrawal_no,
                $row->user->mobile??null,
                $row->user instanceof User ? $row->user->nickname : $row->user->name,
                $row->apply_money,
                $adjust_amount['adjust_amount']??null,
                $row->business_type_rename,
                $row->created_at,
                $row->completion_time,
                $row->admin_id?'已审核':'未审核',
            ];
        }
        return [
            $row->apply_time,
            $row->withdrawal_no,
            $row->park->project_name??null,
            $row->apply_money,
            $adjust_amount['adjust_amount']??null,
            $row->created_at,
            $row->completion_time,
            $row->business_type_rename,
            $row->user->banned_withdraw?'是':'否',
            $row->admin_id?'已审核':'未审核',
            $row->reviewer->name??null,
            $row->audit_time,
            $row->user instanceof User ? $row->user->nickname : $row->user->name,
            $row->user->mobile??null,
        ];
    }
}
