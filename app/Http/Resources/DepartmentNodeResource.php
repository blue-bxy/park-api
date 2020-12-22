<?php

namespace App\Http\Resources;

use App\Http\Resources\Admin\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentNodeResource extends JsonResource
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
            'department_id' => $this->id,
            'name' => $this->name,
            'roles' => $this->whenLoaded('roles', function () {
                return RoleResource::collection($this->roles);
            })
        ];
    }
}
