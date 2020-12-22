<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkCommentResource extends JsonResource
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
            'nickname' => $this->user->nickname,
            'avatar' => $this->user->avatar,
            'content' => $this->content,
            'imgs' => $this->covers,
            'score' => $this->rate,
            'comment_time' => $this->created_at->format('Y-m-d H:i')
        ];
    }
}
