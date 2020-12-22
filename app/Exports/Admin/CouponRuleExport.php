<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Coupons\CouponRule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CouponRuleExport extends ExcelExport implements FromQuery, WithHeadings, WithMapping
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
        return CouponRule::query()->with('user')->search($this->request)->latest();
    }

    public function headings(): array {
        return [
            '序号', '优免规则名称', '优免类型', '抵消上限', '创建人', '创建日期', '当前状态'
        ];
    }

    public function map($row): array {
        return [
            ++$this->rank,
            $row->title,
            CouponRule::$typeMaps[$row->type] ?? null,
            ($row->amount / 100).'元',
            $row->user->name ?? null,
            $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : null,
            $row->is_active ? '启用' : '停用'
        ];
    }
}
