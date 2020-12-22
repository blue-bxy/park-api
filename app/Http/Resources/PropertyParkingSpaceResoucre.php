<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyParkingSpaceResoucre extends JsonResource
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
            'user_id' => $this->user->id,
            'nickname' => $this->user->nickname,
            'mobile' => $this->user->mobile,

            'id_car_type' => $this->id_car_type,
            'id_car_type_name' => $this->id_car_type_name,
            'id_car_number' => $this->id_car_number,
            'id_car_name' => $this->id_car_name,

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
            'allowed_time' => $this->hasAllowed() ? $this->allowed_at->toDateTimeString() : null
        ];
    }
}
