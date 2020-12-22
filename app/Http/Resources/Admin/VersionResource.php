<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class VersionResource extends JsonResource
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
            'id' => $this->id,
            'version_no' => $this->version_no,
            'platform' => $this->platform,
            'update_description' => $this->update_description,
            'resource_url' => $this->resource_url,
            'is_force' => $this->is_force,
            'user_name' => $this->user->name ?? null,
            'date' => $this->created_at->format('Y-m-d')
        ];
    }
}
