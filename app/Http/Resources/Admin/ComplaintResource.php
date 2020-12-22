<?php


namespace App\Http\Resources\Admin;


use App\Models\Parks\Park;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'order_no'=>$this->order_no??null,
            'type'=>$this->type??null,
            'nickname'=>$this->user->nickname,
            'mobile'=>$this->user->mobile,
            'imgurl'=>$this->covers,
            'project_name'=>(Park::select('project_name')->where('id',$this->order->park_id??null)->first()->project_name)??null,
            'content'=>$this->content,
            'handling_state' => $this->handling_state,
            'handling_person' => $this->handling_person,
            'handling_time' => $this->handling_time
        ];
    }
}
