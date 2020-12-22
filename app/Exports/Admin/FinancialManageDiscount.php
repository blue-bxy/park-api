<?php


namespace App\Exports\Admin;


use App\Exports\ExcelExport;
use App\Models\Coupons\Coupon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageDiscount extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $rank = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Coupon::query()->search($this->request)->latest();
    }

    public function headings(): array
    {
        return ['序号', '优惠券编号', '总发布张数', '优免规则', '发放方式', '发放日期', '截止日期', '生效日期', '过期日期'];
    }

    public function map($row): array
    {
        return [
            ++$this->rank,
            $row->no,
            $row->quota,
            $row->rules['rule']['title'] ?? null,
            Coupon::DISTRIBUTION_METHODS[$row->distribution_method] ?? null,
            $row->start_time ? $row->start_time->format('Y-m-d H:i') : null,
            $row->end_time ? $row->end_time->format('Y-m-d H:i') : null,
            $row->valid_start_time ? $row->valid_start_time->format('Y-m-d H:i') : null,
            $row->valid_end_time ? $row->valid_end_time->format('Y-m-d H:i') : null
        ];
    }
}
