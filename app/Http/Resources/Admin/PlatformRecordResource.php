<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PlatformRecordResource extends JsonResource
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
            'date'=>$this->date,
            'business'=>$this->bill_type,
            'business_rename'=>$this->bill_type_name,
            'type'=>$this->type,
            'income'=>$this->income,
            'spending'=>$this->expenses,
            'balance'=>$this->amount,
        ];
    }
}
