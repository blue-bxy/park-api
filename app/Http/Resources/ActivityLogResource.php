<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
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
            'desc' => $this->desc,
            'last_ip' => $this->last_ip,
            'add_time' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => $this->whenLoaded('causer', function () {
                return [
                    'user_id' => $this->causer->id,
                    'user_name' => $this->causer->name
                ];
            })
        ];
    }
}
