<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //车场设置侧边栏菜单
        if ($request->routeIs('admin.parks.sidebar')) {
            return [
                'id' => $this->id,
                'project_name' => $this->project_name,
                'areas' => ParkAreaResource::collection($this->areas)
            ];
        }
        $data = [
            'id' => $this->id,
            'project_name' => $this->project_name,
            'park_number' => $this->park_number,
            'company' => $this->company,
            'group_name' => $this->projectGroup->group_name,
            'city' => $this->park_city == '市辖区' ? $this->park_province : $this->park_province.$this->park_city,
            'carport_count' => (int) optional($this->parkStall)->carport_count,
            'fixed_carport_count' => (int) optional($this->parkStall)->fixed_carport_count,
            'activation_code' => optional($this->parkService)->activation_code,
            'park_operation_state' => $this->park_operation_state,
            'park_property' => $this->park_property,
            'park_state' => $this->park_state,
        ];
        if ($request->routeIs('admin.parks.show')) {
            $data = array_merge($data, [
                'park_name' => $this->park_name,
                'province_id' => $this->province->province_id,
                'province' => $this->province->name,
                'city_id' => $this->country->city_id,
                'city' => $this->park_city,
                'area_id' => $this->country->country_id,
                'area' => $this->country->name,
                'project_address' => $this->project_address,
                'longitude' => $this->longitude,
                'latitude' => $this->latitude,
                'entrance_coordinate' => $this->entrance_coordinate,
                'exit_coordinate' => $this->exit_coordinate,
                'park_type' => $this->park_type,
                'park_cooperation_type' => $this->park_cooperation_type,
                'park_height_permitted' => $this->park_height_permitted,
                'carport_count' => $this->parkStall->carport_count,
                'fixed_carport_count' => $this->parkStall->fixed_carport_count,
                'temporary_carport_count' => $this->parkStall->temporary_carport_count,
                'order_carport' => $this->parkStall->order_carport,
                'charging_pile_carport' => $this->parkStall->charging_pile_carport,
                'lanes_count' => $this->parkStall->lanes_count,
                'free_time' => $this->parkStall->free_time,
                'park_operation_time' => $this->parkStall->park_operation_time,
                'fee_string' => $this->parkStall->fee_string,
                'map_fee' => $this->parkStall->map_fee,
                'salesman_number' => $this->parkService->salesman_number,
                'sales_name' => $this->parkService->sales_name,
                'sales_phone' => $this->parkService->sales_phone,
                'contract_no' => $this->parkService->contract_no,
                'activation_code' => $this->parkService->activation_code,
                'contract_start_period' => $this->parkService->contract_start_period,
                'contract_end_period' => $this->parkService->contract_end_period
            ]);
        }
        return $data;
    }
}
