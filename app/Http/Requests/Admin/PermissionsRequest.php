<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;

class PermissionsRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                return [];
                break;
            case 'POST':
                return [
                    'name'         => 'required|unique:permissions,name',
                    'display_name' => 'required',
                    'parent_id' => 'required|integer',
                ];
                break;
            case 'PUT':
            case 'PATCH':
                return [
                    'name'         => 'required|unique:permissions,name,'.request('permission.id'),
                    'display_name' => 'required',
                    'parent_id' => 'required|integer',
                ];
                break;
            default:
                break;
        }
    }

    public function messages()
    {
        return [
            'name.required' => '路由不能为空',
            'name.unique' => '路由已存在',
            'display_name.required' => '显示名称不能为空',
            'parent_id.required' => '上级ID必须',
        ];
    }
}
