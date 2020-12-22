<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserComplaintResource extends JsonResource
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
            // 'title' => $this->title,
            'content' => $this->content,
            'imgurl' => $this->covers,
            'state' => $this->result,
            'state_name' => $this->result_rename,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
