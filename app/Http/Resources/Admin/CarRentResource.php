<?php

namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class CarRentResource extends JsonResource
{
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
            'park_name' => $this->parks->project_name ?? null,
            'rent_start_time' => $this->rent_start_time,
            'rent_end_time' => $this->rent_end_time,
            'time_horizon' => $this->start . '-' . $this->stop,
            'rent_time' => $this->rent_time,
            'rent_num' => $this->rent_num,
            'rent_price' => $this->rent_price,
            'amount' => $amount,
            'rent_status' => $this->rent_status,
            'user_name' => $this->user->nickname ?? null,
            'rent_no' => $this->rent_no
        ];
    }
}
