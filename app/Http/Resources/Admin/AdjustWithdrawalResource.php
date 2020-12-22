<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AdjustWithdrawalResource extends JsonResource
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
            'withdrawal_no'   => $this->withdrawal_no,
            'apply_time'      => $this->apply_time,
            'apply_money'     => $this->apply_money,
            'user'            => $this->when($this->user, function () {
                return [
                    'nickname' => $this->nickname(),
                    'user_id'  => $this->user->getKey(),
                ];
            }),
            'park'            => $this->whenLoaded('park', function () {
                return [
                    'park_id'      => $this->park->id,
                    'project_name' => $this->park->project_name,
                ];
            }),
            'completion_time' => $this->completion_time,
            'status'=>$this->admin_id?'已审核':'未审核',
        ];
    }

    protected function nickname()
    {
        return $this->user instanceof User ? $this->user->nickname : $this->user->name;
    }
}
