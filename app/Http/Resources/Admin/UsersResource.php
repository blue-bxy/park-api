<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'user_id' => $this->id,
            'nickname' => $this->nickname,
            'avatar' => $this->avatar(),
            'sex' => $this->gender,
            'mobile' => $this->mobile,
            'cars' => $this->whenLoaded('cars', function () {
                return UserCarsResource::collection($this->cars);
            })
        ];
    }
}
