<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RecordResource extends JsonResource
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
            'withdrawal_no'=>$this->withdrawal->withdrawal_no ?? null,
            'apply_money'=>$this->withdrawal->apply_money ?? null,
            'park_name'=>$this->withdrawal->park->project_name ?? null,
            'record_no'=>$this->record_no,
            'adjust_type'=>$this->adjust_type,
            'adjust_amount'=>$this->adjust_amount,
            'reason'=>$this->reason,
            'is_loss'=>$this->is_loss,
            'operator'=>$this->operator,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
