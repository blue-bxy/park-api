<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountManageResource extends JsonResource
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
            'park_name'=>$this->park->project_name ?? null,
            'account_type'=>$this->account_type,
            'bank_name'=>$this->bank_name,
            'bank_code'=>$this->bank_code,
            'sub_branch'=>$this->sub_branch,
            'account'=>$this->account,
            'account_name'=>$this->account_name,
            'account_province_city'=>$this->account_province . $this->account_city,
            'audit_status' => $this->audit_status,
            'contract_num'=>$this->contract_id,
            'time'=>$this->updated_at->format('Y-m-d H:i:s'),
            'synchronization_type'=>$this->synchronization_type,
        ];
    }
}
