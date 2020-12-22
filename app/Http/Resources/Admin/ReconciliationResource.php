<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ReconciliationResource extends JsonResource
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
            'date' => $this->date,
            'park_name'=>$this->park->project_name,
            'order_type'=>$this->bill_type_name,
            // 'income'=>number_format($this->income / 100, 2),
            'income'=> $this->income
        ];
    }
}
