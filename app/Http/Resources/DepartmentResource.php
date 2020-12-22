<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'guard_name' => $this->guard_name,
            'guard_rename' => $this->guard_rename,
            'positions' => $this->whenLoaded('positions', function () {
                return PositionResource::collection($this->positions);
            })
        ];
    }
}
