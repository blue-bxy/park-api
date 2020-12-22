<?php

namespace App\Http\Resources\Admin;

use App\Models\Financial\Record;
use App\Models\Financial\Withdrawal;
use App\Models\Property;
use App\Models\Users\UserRefund;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplyDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->order instanceof Withdrawal){
            $adjust_amount=Record::where('withdrawal_id',$this->order_id)->orderBy('id','desc')->first('adjust_amount');
            $amount=$adjust_amount['adjust_amount']?$adjust_amount['adjust_amount']:$this->order->apply_money;
            if($this->order->person_type==1 && $this->order->user_type ==Property::class){
                return [
                    'park_name'=>$this->order->park->project_name??null,
                    'account_name'=>$this->order->park->account->account_name??null,
                    'account'=>$this->order->park->account->account??null,
                    'bank_name'=>$this->order->park->account->bank_name??null,
                    'amount'=>$amount??null,
                ];
            }else{
                return [
                    'user_name'=>$this->order->user->nickname??null,
                    'amount'=>$amount??null,
                    'account'=>$this->order->account ?? null,
                    'account_name' => $this->order->account_name ?? null,
                    'way'=>$this->way()??null,
                ];
            }
        }elseif ($this->order instanceof UserRefund){
            return[
                'user_name'=>$this->order->user->nickname??null,
                'amount'=>$this->order->refunded_amount??null,
                'account'=>$this->order->transfer_account ?? null,
                'way'=>$this->way()??null,
            ];
        }
    }

    public function UserAccount(){
        $account=$this->order->user->UserAccount->account??null;
        $openid=$this->order->user->UserAccount->openid??null;
        return $account?$account:$openid;
    }

    public function way(){
        $account=$this->order->user->UserAccount->account??null;
        return $account?'支付宝':'微信';
    }
}
