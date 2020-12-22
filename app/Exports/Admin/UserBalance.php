<?php
/**
 * User: 马超
 * Date: 2020/6/30
 * Time: 16:43
 */

namespace App\Exports\Admin;


use App\Exports\ExcelExport;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserBalance extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = \App\Models\Users\UserBalance::query();

        $query->with('order','user');

        return $query->search($this->request);
    }

    public function headings(): array
    {

        return ['用户名','业务类型','通道交易号','来源订单号','流水金额','支付方式','创建时间'];
    }
    public function map($row): array
    {


        // 支付方式：1-余额 2-微信 3-支付宝
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
                $this->gateway = '余额';
        }

        return[
            User::where('id',$row->user_id)->value('nickname'),
            $row->body,
            $row->trade_no,
            $row->order_no,
            $row->amount,
            $row->gateway,
            $row->created_at
        ];
    }
}
