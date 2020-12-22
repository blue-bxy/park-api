<?php


namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class ParkDataResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'project_name' => $this->project_name,
            'park_name' => $this->park_name,
            'park_number' => $this->park_number,
            'property_id' => $this->property_id,
            'project_group_id' => $this->project_group_id,
            'province' => $this->park_province,
            'province_id' => $this->province->province_id,
            'city' => $this->park_city,
            'city_id' => $this->city->city_id,
            'area' => $this->park_area,
            'area_id' => $this->country->country_id,
            'project_address' => $this->project_address,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'entrance_coordinate' => $this->entrance_coordinate,
            'exit_coordinate' => $this->exit_coordinate,
            'park_type' => $this->park_type,
            'park_cooperation_type' => $this->park_cooperation_type,
            'park_device_type' => $this->park_device_type,
            'park_property' => $this->park_property,
            'park_operation_state' => $this->park_operation_state,
            'park_state' => $this->park_state,
            'park_height_permitted' => $this->park_height_permitted,
            'carport_count' => $this->parkStall->carport_count,
            'fixed_carport_count' => $this->parkStall->fixed_carport_count,
            'temporary_carport_count' => $this->parkStall->temporary_carport_count,
            'order_carport' => $this->parkStall->order_carport,
            'charging_pile_carport' => $this->parkStall->charging_pile_carport,
            'lanes_count' => $this->parkStall->lanes_count,
            'park_operation_time' => $this->parkStall->park_operation_time,
            'fee_string' => $this->parkStall->fee_string,
            'map_fee' => $this->parkStall->map_fee,
            'salesman_number' => $this->parkService->salesman_number,
            'sales_name' => $this->parkService->sales_name,
            'sales_phone' => $this->parkService->sales_phone,
            'contract_no' => $this->parkService->contract_no,
            'activation_code' => $this->parkService->activation_code,
            'contract_period' => $this->parkService->contract_period,

        ];
    }
}
