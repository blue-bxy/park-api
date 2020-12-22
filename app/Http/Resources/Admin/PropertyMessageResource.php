<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyMessageResource extends JsonResource
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
            'title' => $this->title,
            'park_type' => $this->park_type,
            'content' => $this->content,
            'admin' => $this->admin->name ?? null,
            'created_at' => $this->created_at->format('Y-m-d H:i')
        ];
    }
}
