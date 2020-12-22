<?php

namespace App\Http\Resources\Property;

use Illuminate\Http\Resources\Json\JsonResource;

class CarRentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->rent_status == 0){

            $this->rent_status = '停用';
        }else{

            $this->rent_status = '启用';
        }

        $apt = ($this->apt ? $this->apt->sum('owner_fee') : null);

        $stop = $this->carApt? ($this->carApt->map->userOrder ? ($this->carApt->map->userOrder->map->divide->sum('owner_fee')):null): null;

        $amount = $apt + $stop;

        return [
            'id' => $this->id,
            'user_name' => $this->user->nickname ?? null,
            'rent_no' => $this->rent_no,
            'rent_start_time' => $this->rent_start_time,
            'rent_end_time' => $this->rent_end_time,
            'time_quantum' => $this->start . '-' . $this->stop,
            'rent_time' => $this->carApt->sum('apt_time'),
            'rent_num' => $this->rent_num,
            'rent_price' => $this->rent_price,
            'amount' => $amount,
            'rent_status' => $this->rent_status,
            'user_name' => $this->user->nickname ?? null,
            'rent_no' => $this->rent_no
        ];
    }
}
