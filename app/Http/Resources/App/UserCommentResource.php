<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCommentResource extends JsonResource
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
            'imgurl' => $this->imgurl,
            'content' => $this->content,
            'rate' => $this->rate,
            'user_id' => $this->user_id,
            'order_id' => $this->order_id,

        ];
    }
}
