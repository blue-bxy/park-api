<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class ParkAccountRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'park_name' => 'required|string',
            'account_type'=>'required|numeric',
            'bank_name' => 'required|string',
            'bank_code' => 'required|string',
            'province_id' => 'required|numeric',
            'city_id' => 'required|numeric',
            'sub_branch' => 'required|string',
            'account' => 'required',
            'account_name' => 'required|string',
            'contract_num'=>'required|string'
        ];
    }

    public function attributes()
    {
        return [
            'park_name' => '车场名称',
            'bank_name' => '开户行',
            'sub_branch' => '支行',
            'bank_code' => '银行编码',
            'account_province' => '账户所在省',
            'account_city' => '账户所在市',
            'account' => '银行账户',
            'account_name' => '姓名',
            'contract_num'=>'合同编号'
        ];
    }
}
