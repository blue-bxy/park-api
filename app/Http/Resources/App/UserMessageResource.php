<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserMessageResource extends JsonResource
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
            'content' => $this->content,
            'imgurl' => $this->imgurl,
            'type' => $this->type,
            'has_read' => $this->hasRead(),
            'add_time' => $this->created_at->timestamp,
        ];
    }
}
