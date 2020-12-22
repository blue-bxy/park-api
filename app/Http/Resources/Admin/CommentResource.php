<?php


namespace App\Http\Resources\Admin;


use App\Models\Parks\Park;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {

        switch($this->audit_status){
            case '待审核':
                $status = 1;
                break;
            case '已通过':
                $status = 2;
                break;
            case '未通过':
                $status = 3;
                break;
        }

        return [
            'id'=>$this->id,
            'order_no'=>$this->order->order_no ?? null,
            'park_name'=>$this->park->project_name ?? null,
            'user_name'=>$this->user->nickname ?? null,
            'user_moblie'=>$this->user->mobile ?? null,
            'content'=>$this->content,
            'img'=>$this->covers,
            'rate'=>$this->rate,
            'status' => $status,
            'audit_status'=>$this->audit_status,
            'auditor'=>$this->auditor,
            'audit_time'=>$this->audit_time,
            'refuse_reason'=>$this->refuse_reason,

        ];
    }
}
