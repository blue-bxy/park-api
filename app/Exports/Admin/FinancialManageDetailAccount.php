<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Users\UserBalance;
use App\Models\Users\UserPaymentLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageDetailAccount extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return UserBalance::query()
            ->with('order','user')
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['用户名','业务类型','通道交易号','来源订单号','流水金额','支付方式','创建时间'];
    }

    public function map($row): array
    {
        switch($row->gateway){
            case 'wechat':
                $row->gateway = '微信';
                break;
            case 'wx_npd':
                $row->gateway = '微信';
                break;
            case 'wx_app':
                $row->gateway = '微信';
                break;
            case 'ali_app':
                $row->gateway = '支付宝';
                break;
            case 'ali_wap':
                $row->gateway = '支付宝';
                break;
            case 'ali_web':
                $row->gateway = '支付宝';
                break;
            case 'ali_npd':
                $row->gateway = '支付宝';
                break;
            default:
                $row->gateway = '余额';
        }
        return [
            $row->user->nickname ?? null,
            $row->type,
            $row->trade_no,
            $row->order_no,
            $row->amount,
            $row->gateway,
            $row->created_at->format('Y-m-d H:i')
        ];
    }
}
