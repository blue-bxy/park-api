<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {


        // 支付方式：1-余额 2-微信 3-支付宝
        switch($this->gateway){
            case 'wechat':
                $this->gateway = '微信';
                break;
            case 'wx_npd':
                $this->gateway = '微信';
                break;
            case 'wx_app':
                $this->gateway = '微信';
                break;
            case 'ali_app':
                $this->gateway = '支付宝';
                break;
            case 'ali_wap':
                $this->gateway = '支付宝';
                break;
            case 'ali_web':
                $this->gateway = '支付宝';
                break;
            case 'ali_npd':
                $this->gateway = '支付宝';
                break;
            default:
                $this->gateway = '余额';
        }

        $add = ['withdraw','subscribe','subscribe_renewal'];

        $sub = ['charge','subscribe_refund'];

        if(in_array($this->type,$add)){
            $amount = '-' . $this->amount;
        }

        if(in_array($this->type,$sub)){
            $amount = '+' . $this->amount;
        }

        return [
            'id' => $this->id,
            'user_name' => $this->user->nickname ?? null,
            'type' => $this->type,
            'body' => $this->body,
            'trade_no' => $this->trade_no,
            'order_no' => $this->order_no,
            'money_amount' => $amount,
            'gateway' => $this->gateway,
            'created_at'=> $this->created_at->format('Y-m-d H:i')
        ];
    }
}
