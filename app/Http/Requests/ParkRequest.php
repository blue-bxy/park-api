<?php

namespace App\Http\Requests;

class ParkRequest extends BaseRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = [
            'project_name' => 'string|min:1',
            'company' => 'string|min:1',
            'province_id' => 'integer',
            'city_id' => 'integer',
            'area_id' => 'integer',
            'project_address' => 'string|min:1',
            'park_type' => 'integer',
            'park_cooperation_type' => 'integer',
            'park_property' => 'integer',
            'park_operation_state' => 'integer',
            'park_state' => 'integer',
            'park_height_permitted' => 'numeric',
            'carport_count' => 'integer|min:0',
            'lanes_count' => 'integer',
            'free_time' => 'integer',
            'fixed_carport_count' => 'integer|min:0',
            'temporary_carport_count' => 'integer|min:0',
            'order_carport' => 'integer|min:0',
            'charging_pile_carport' => 'integer|min:0',
            'park_operation_time' => 'date',
            'fee_string' => 'string',
            'map_fee' => 'string',
            'salesman_number' => 'string',
            'sales_name' => 'string',
            'sales_phone' => 'string',
            'contract_no' => 'string',
            'activation_code' => 'string',
            'contract_start_period' => 'date',
            'contract_end_period' => 'date',
        ];
        if ($this->routeIs('admin.parks.store')) {
            $rules = array_merge($rules, [
                'project_name' => 'required|string|min:1',
                'park_name' => 'required|nullable',
                'province_id' => 'required|integer',
                'city_id' => 'required|integer',
                'area_id' => 'required|integer',
                'project_address' => 'required|string|min:1',
                'park_type' => 'required|integer',
                'park_cooperation_type' => 'required|integer',
                'park_property' => 'required|integer',
                'park_operation_state' => 'required|integer',
                'carport_count' => 'required|integer|min:0',
                'lanes_count' => 'required|integer|min:1',
                'fixed_carport_count' => 'required|integer|min:0',
                'temporary_carport_count' => 'required|integer|min:0',
                'order_carport' => 'required|integer|min:0',
                'charging_pile_carport' => 'required|integer|min:0',
                'park_operation_time' => 'required|date',
                'fee_string' => 'required|string',
                'map_fee' => 'required|string',
                'salesman_number' => 'required|string',
                'sales_name' => 'required|string',
                'sales_phone' => 'required|string',
                'contract_no' => 'required|string',
                'activation_code' => 'required|string',
                'contract_start_period' => 'required|date',
                'contract_end_period' => 'required|date',
            ]);
        }
        return $rules;
    }

    public function attributes() {
        return [
            'project_name' => '项目名',
            'park_name' => '停车场名',
            'park_number' => '停车场编号',
            'project_group_id' => '集团名',
            'province_id' => '省',
            'city_id' => '市',
            'area_id' => '区',
            'project_address' => '项目地址',
            'longitude' => '经度',
            'latitude' => '纬度',
            'entrance_coordinate' => '入口坐标',
            'exit_coordinate' => '出口坐标',
            'park_type' => '停车场类型',
            'park_cooperation_type' => '停车场合作类型',
            'park_property' => '停车场属性',
            'park_operation_state' => '停车场运营状态',
            'park_state' => '停车场状态',
            'park_height_permitted' => '停车场限高',
            'carport_count' => '总车位数',
            'lanes_count' => '总车道数',
            'fixed_carport_count' => '固定车位',
            'temporary_carport_count' => '临停车位数',
            'order_carport' => '预约车位',
            'charging_pile_carport' => '充电桩数',
            'park_operation_time' => '停车场运营时间',
            'fee_string' => '文字费率',
            'map_fee' => '地图费率',
            'salesman_number' => '业务员工号',
            'sales_name' => '业务员名字',
            'sales_phone' => '业务员电话',
            'contract_no' => '合同编号',
            'activation_code' => '激活码',
            'contract_start_period' => '合同开始日期',
            'contract_end_period' => '合同结束日期'
        ];
    }

}
