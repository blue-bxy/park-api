<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
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
            'position_id' => $this->id,
            'name' => $this->name,
            'guard_name' => $this->guard_name,
            'guard_rename' => $this->guard_rename,
            'department' => $this->whenLoaded('department', function () {
                return new DepartmentResource($this->department);
            })
        ];
    }
}
