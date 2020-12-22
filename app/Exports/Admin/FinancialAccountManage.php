<?php
/**
 * User: 马超
 * Date: 2020/5/29
 * Time: 15:23
 */

namespace App\Exports\Admin;


use App\Exports\ExcelExport;
use App\Models\Financial\AccountManage;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialAccountManage extends ExcelExport implements FromQuery, WithHeadings , WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = AccountManage::query();

        $query->with(['park','property']);

        return $query->search($this->request);
    }

    public function headings(): array
    {

        return ['车场名称','账户名称','账号','账户类型','开户行','支行','银行编码','账户所在省市','业务管理状态','合同编号','同步状态','修改时间'];
    }

    public function map($row): array
    {
        return[
            $row->park->project_name ?? null,
            $row->account_name,
            $row->account,
            $row->account_type,
            $row->bank_name,
            $row->sub_branch,
            $row->bank_code,
            $row->account_province .  $row->account_city,
            $row->audit_status,
            $row->contract_id,
            $row->synchronization_type,
            $row->updated_at,
        ];
    }
}
