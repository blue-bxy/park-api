<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserParkingSpaceRespurce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'parking_id' => $this->id,
            'id_card_name' => $this->id_card_name,
            'user' => $this->when($this->user, function () {
                return [
                    'nickname' => $this->user->nickname,
                    'mobile' => $this->user->mobile
                ];
            }),

            'id_car_type' => $this->id_card_type,
            'id_car_type_name' => $this->id_card_type_name,
            'id_car_number' => $this->id_card_number,
            'id_car_name' => $this->id_card_name,

            'number' => $this->number,

            'park' => $this->when($this->park, function () {
                return [
                    'park_id' => $this->park->id,
                    'park_name' => $this->park->park_name
                ];
            }),

            'property' => $this->when($this->park->property, function () {
                $property = $this->park->property;
                return [
                    'property_id' => $property->id,
                    'property_name' => $property->name,
                ];
            }),

            'certificate_covers' => $this->certificate_covers,

            'contract_covers' => $this->contract_covers,
            // 物业审核状态
            'status' => $this->status,
            'status_rename' => $this->status_rename,
            // 申请时间
            'apply_time' => $this->created_at->toDateTimeString(),
            'has_handle' => $this->hasHandle(),
            // 物业审核时间
            'handle_time' => $this->hasHandle() ? $this->launch_time->toDateTimeString() : null,
            //
            'has_allowed' => $this->hasAllowed(),
            'allowed_time' => $this->hasAllowed() ? $this->allowed_at->toDateTimeString() : null,
            'project_name' => $this->park->project_name ?? null
        ];
    }
}
