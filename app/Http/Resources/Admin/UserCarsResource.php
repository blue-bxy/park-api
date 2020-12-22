<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCarsResource extends JsonResource
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
            'car_id' => $this->id,
            'car_num' => $this->car_number,
            'is_default' => $this->is_default,
            'is_verify' => $this->is_verify,
        ];
    }
}
