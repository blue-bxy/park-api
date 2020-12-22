<?php

namespace App\Http\Resources\Admin;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPaymentLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //1-充值 2-支付 3-提现 4-退款
        if($this->business_type == 1){
            $this->business_type = '充值';
        }elseif ($this->business_type==2){
            $this->business_type = '支付';
        }elseif ($this->business_type==3){
            $this->business_type = '提现';
        }else{
            $this->business_type = '退款';
        }

        // 1-余额 2-微信 3-支付宝
        if($this->account_type == 1){
            $this->account_type = '余额';
        }elseif ($this->account_type==2){
            $this->account_type = '微信';
        }else{
            $this->account_type = '支付宝';
        }
        // 支付类型:1-余额抵扣，2-第三方抵扣，3-积分抵扣
        if($this->pay_type == 1){
            $this->pay_type = '余额抵扣';
        }elseif ($this->pay_type == 2){
            $this->pay_type = '第三方抵扣';
        }else{
            $this->pay_type = '积分抵扣';
        }

        return [
            'id' => $this->id,
            'user_name' => $this->user->nickname,
            'business_type' => $this->business_type,
            'trade_no' => $this->trade_no,
            'order_no' => $this->order_no,
            'money_amount' => $this->money_amount,
            'account_type' => $this->account_type,
            'pay_type' => $this->pay_type,
            'created_at'=> $this->created_at
        ];
    }
}
