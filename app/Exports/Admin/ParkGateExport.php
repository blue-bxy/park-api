<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Parks\ParkGate;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkGateExport extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var int
     */
    protected $rank = 0;

    /**
     * ParkGateExport constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query() {
        return ParkGate::query()->with('park')->search($this->request);
    }

    /**
     * @return string[]
     */
    public function headings(): array {
        return ['序号', '车场名称', '控制方案', '道闸系统品牌', '软件版本', '对接方式', '停车费电子支付模式', '当前状态'];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array {
        return [
            ++$this->rank,
            $row->park->project_name ?? null,
            ParkGate::PROGRAMMES[$row->programme] ?? null,
            $row->brand,
            $row->version,
            ParkGate::MODES[$row->mode] ?? null,
            ParkGate::PAYMENT_MODES[$row->payment_mode] ?? null,
            $row->is_active ? '启用' : '停用'
        ];
    }
}
