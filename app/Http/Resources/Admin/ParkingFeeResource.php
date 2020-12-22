<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkingFeeResource extends JsonResource
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
            'id'=>$this->id,
            'park_name'=>$this->park->project_name??null,
            'fee'=>$this->fee.'%',
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'user_name'=>$this->admin->name??null,
        ];
    }
}
