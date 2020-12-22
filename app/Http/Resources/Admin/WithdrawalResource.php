<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'withdrawal_no'   => $this->withdrawal_no,
            'apply_time'      => $this->apply_time,
            'apply_money'     => $this->apply_money,
            // 'applicant'=>$this->applicant,
            'park'            => $this->whenLoaded('park', function () {
                return [
                    'park_id'      => $this->park->id,
                    'project_name' => $this->park->project_name,
                ];
            }),
            'user'            => $this->when($this->user, function () {
                return [
                    'nickname' => $this->nickname(),
                    'user_id'  => $this->user->getKey(),
                ];
            }),
            'account'         => $this->account,
            'status'          => $this->status,
            'status_rename'   => $this->status_rename,
            'completion_time' => $this->completion_time,
            'reviewer'        => $this->when($this->reviewer, function () {
                return [
                    'reviewer_id' => $this->reviewer->id,
                    'name'        => $this->reviewer->name,
                    'audit_time'      => $this->audit_time,
                ];
            }),

            'remark'          => $this->remark,

        ];
    }

    protected function nickname()
    {
        return $this->user instanceof User ? $this->user->nickname : $this->user->name;
    }
}
