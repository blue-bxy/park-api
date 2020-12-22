<?php

namespace App\Http\Resources\Admin;

use App\Models\Financial\Record;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
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
            'apply_time'      =>$this->apply_time,
            'withdrawal_no'   =>$this->withdrawal_no,
            'park'            => $this->whenLoaded('park', function () {
                return [
                    'park_id'      => $this->park->id,
                    'project_name' => $this->park->project_name,
                ];
            }),
            'apply_money'     => $this->apply_money,
            'adjust_amount'=>Record::where('withdrawal_id',$this->id)->orderBy('id','desc')->first('adjust_amount')??null,
            'created_at'=>date('Y-m-d H:i:s',strtotime($this->created_at)),
            'completion_time' => $this->completion_time,
            'business_type'=>$this->business_type,
            'business_type_rename'   => $this->business_type_rename,
            'is_banned'=>$this->user->banned_withdraw?'是':'否',
            'status'=>$this->status,
            'status_rename'   => $this->status_rename,
            'audit_status'=>$this->admin_id?'已审核':'未审核',
            'reviewer'        => $this->when($this->reviewer, function () {
                return [
                    'reviewer_id' => $this->reviewer->id,
                    'name'        => $this->reviewer->name,
                    'audit_time'      => $this->audit_time,
                ];
            }),
            'user'            => $this->when($this->user, function () {
                return [
                    'nickname' => $this->nickname(),
                    'user_id'  => $this->user->getKey(),
                    'mobile'=>$this->user->mobile,
                ];
            }),
        ];
    }

    protected function nickname()
    {
        return $this->user instanceof User ? $this->user->nickname : $this->user->name;
    }
}
