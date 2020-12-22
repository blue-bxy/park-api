<?php


namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * 物业用户返回结构处理
 * Class PropertiesResource
 * @package App\Http\Resources\Admin
 */
class PropertiesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'property_id' => $this->id,
            'park_name' => $this->park->project_name ?? null ,
            'recode' => $this->code,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'create_time' => $this->created_at->toDateTimeString(),
            'departments' => $this->departments->map->only(['id', 'name']),
            'roles' => $this->roles->map->only(['id', 'name', 'display_name']),
        ];
    }
}
