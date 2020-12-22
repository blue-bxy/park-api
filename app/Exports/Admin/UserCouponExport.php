<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Users\UserCoupon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserCouponExport extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $rank;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->rank = 0;
    }

    public function query() {
        return UserCoupon::query()->with(['user', 'order'])->search($this->request)->latest();
    }

    public function headings(): array {
        return [
            '序号', '优惠券编号', '领取日期', '优惠券名称', '注册用户', '注册手机', '使用状态', '抵扣金额', '关联订单', '发放渠道'
        ];
    }

    public function map($row): array {
        return [
            ++$this->rank,
            $row->no,
            $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : null,
            $row->title,
            $row->user->nickname ?? null,
            $row->user->mobile ?? null,
            UserCoupon::$statusMaps[$row->status] ?? null,
            $row->status == 'used' ? ($row->amount / 100).'元' : null,
            $row->order->no ?? null,
            $row->distribution_method
        ];
    }
}
