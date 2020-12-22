<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RechargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->gateway == 'wx_app'){
            $this->gateway = '微信';
        }elseif($this->gateway == 'ali_app' || $this->gateway == 'ali_web'){
            $this->gateway = '支付宝';
        }else{
            $this->gateway = '其它';
        }
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'account'=>$this->user->nickname??null,
            'mobile'=>$this->user->mobile??null,
            'create_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'amount'=>$this->amount,
            'balance'=>$this->user->balance??null,
            'serial_number'=>$this->transaction_id,
            'no'=>$this->no,
            'method'=>$this->gateway,
        ];
    }
}
