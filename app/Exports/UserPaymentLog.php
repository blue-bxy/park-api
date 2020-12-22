<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserPaymentLog extends ExcelExport implements FromQuery, WithHeadings ,WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = \App\Models\Users\UserPaymentLog::query();

        $query->with('order');

        return $query->search($this->request);
    }

    public function headings(): array
    {

        return ['用户名','业务类型','通道交易号','来源订单号','流水金额','通道类型','支付类型','创建时间'];
    }
    public function map($row): array
    {
        //1-充值 2-支付 3-提现 4-退款
        if($row->business_type==1){
            $row->business_type = '充值';
        }elseif ($row->business_type==2){
            $row->business_type = '支付';
        }elseif ($row->business_type==3){
            $row->business_type = '提现';
        }else{
            $row->business_type = '退款';
        }

        // 1-余额 2-微信 3-支付宝
        if($row->account_type==1){
            $row->account_type = '余额';
        }elseif ($row->account_type==2){
            $row->account_type = '微信';
        }else{
            $row->account_type = '支付宝';
        }
        // 支付类型:1-余额抵扣，2-第三方抵扣，3-积分抵扣
        if($row->pay_type == 1){
            $row->pay_type = '余额抵扣';
        }elseif ($row->pay_type == 2){
            $row->pay_type = '第三方抵扣';
        }else{
            $row->pay_type = '积分抵扣';
        }

        return [
            (User::where('id',$row->user_id)->pluck('nickname'))[0],
            $row->business_type,
            $row->trade_no,
            $row->order_no,
            $row->money_amount,
            $row->account_type,
            $row->pay_type,
            $row->created_at
        ];
    }
}
