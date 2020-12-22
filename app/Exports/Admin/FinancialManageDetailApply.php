<?php

namespace App\Exports\Admin;

use App\Exports\ExcelExport;
use App\Models\Financial\Apply;
use App\Models\Users\UserPaymentLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialManageDetailApply extends ExcelExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    protected $status;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Apply::query()
            ->search($this->request);
    }

    public function headings(): array
    {
        return ['付款批次号','申请付款金额','申请付款笔数','成功付款笔数','业务类型','付款对象类型','提交结果',
            '处理状态','申请时间','付款时间','完成日期','经办人','付款通道'];
    }

    public function map($row): array
    {
        return [
            $row->no,
            $row->amount,
            $row->payment_number,
            $row->success_number,
            $row->business_type,
            $row->person_type,
            $row->submit,
            $row->status,
            $row->apply_time,
            $row->payment_time,
            $row->complete_time,
            $row->agent,
            $row->channel,
        ];
    }
}
