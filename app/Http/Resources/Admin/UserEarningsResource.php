<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use App\Models\Users\UserOrder;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEarningsResource extends JsonResource
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
            'id' => $this->id,
            'park_id' => $this->park_id,
            'park_name' => $this->park->project_name ?? null,
            'income' => $this->amount,
            'fee' => $this->platform_fee,
            'date' => $this->date,
        ];
    }
}
