<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\DepartmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'role_id' => $this->id,
            'role_name' => $this->name,
            'display_name' => $this->display_name,
            'guard_name' => $this->guard_name,
            'guard_rename' => $this->guard_rename,
            // 'permissions' => $this->permissions->map->only(['id', 'name', 'display_name']),
            'departments' => $this->whenLoaded('departments', function () {
                return DepartmentResource::collection($this->departments);
            })
        ];
    }
}
