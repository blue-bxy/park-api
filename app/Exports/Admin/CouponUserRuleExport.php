<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Coupons\CouponUserRule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CouponUserRuleExport extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $rank = 0;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->rank = 0;
    }

    public function query() {
        return CouponUserRule::query()->search($this->request)->latest();
    }

    public function headings(): array {
        return [
            '序号', '用户规则名称', '活跃度', '老用户回归', '新注册用户', '创建日期', '当前状态'
        ];
    }

    public function map($row): array {
        return [
            ++$this->rank,
            $row->title,
            $row->is_activity_active ? '启用' : '不启用',
            $row->is_regression_active ? '启用' : '不启用',
            $row->is_new_user ? '启用' : '不启用',
            $row->created_at ? $row->created_at->format('Y-m-d H:i') : null,
            $row->is_active ? '启用' : '不启用'
        ];
    }
}
