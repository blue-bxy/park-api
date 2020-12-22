<?php


namespace App\Exports;


use App\Models\Parks\Park;
use App\Models\Coupons\Coupon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Discount extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Coupon::query()
            ->search($this->request)
            ->where('park_id',$this->request->user()->park->id)->Where('publisher_type','App\Models\Property')->with('publisher')->latest();
    }

    public function headings(): array
    {
        return ['序号','优惠券编号','发布张数','发布有效期','发布时间','发布人员','优惠券类型','优惠券规则'];
    }

    public function map($row): array
    {
        switch ($row->coupon_rule_type)
        {
            case 1:
                $row->coupon_rule_type = '小时优惠券';
                break;


            case 2:
                $row->coupon_rule_type = '现金优惠券';
                break;

            case 3:
                $row->coupon_rule_type = '折扣优惠券';
                break;

            case 4:
                $row->coupon_rule_type = '全免券';
                break;
        }

        return [
            $row->id,
            $row->no,
            $row->max_receive_num,
            $row->end_time,
            $row->start_time,
            $row->publisher->name,
            $row->coupon_rule_type,
            $row->coupon_rule_value,

        ];

    }
}
