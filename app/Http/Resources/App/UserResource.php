<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
			'imgurl' => $this->avatar,
            'nick_name' => $this->nickname,
            'cellphone' => $this->mobile,
            'sex' => $this->sex,
		];
    }
}
