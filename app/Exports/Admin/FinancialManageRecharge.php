<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Recharge;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageRecharge extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query=Recharge::query();
        $query->where('status','paid');
        return  $query->search($this->request)->with('user')->orderBy('id','desc');
    }

    public function headings(): array
    {
        return ['用户名','手机号','充值时间','充值金额','账户金额','流水号','通道交易号','支付方式'];
    }

    public function map($row): array
    {
        if($row->gateway == 'wx_app'){
            $row->gateway = '微信';
        }elseif($row->gateway == 'ali_app' || $row->gateway == 'ali_web'){
            $row->gateway = '支付宝';
        }else{
            $row->gateway = '其它';
        }
        return [
            $row->user->nickname??null,
            $row->user->mobile??null,
            $row->created_at->format('Y-m-d H:i:s'),
            $row->formatAmount($row->amount),
            $row->user->balance??null,
            $row->transaction_id,
            $row->no,
            $row->gateway,
        ];

    }
}
