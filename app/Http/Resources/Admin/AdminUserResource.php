<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
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
            'admin_id' => $this->id,
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
