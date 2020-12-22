<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $data = $this->extras;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'restrict_coupon' => $data->restrict_coupon ?? null,
            'content' => $this->content,
            'admin' => $this->admin->name,
            'send_time' => $this->send_time ?? $this->created_at->format('Y-m-d H:i')
        ];
    }
}
