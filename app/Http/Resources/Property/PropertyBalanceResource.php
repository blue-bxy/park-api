<?php

namespace App\Http\Resources\Property;

use App\Models\Property;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyBalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($request->routeIs('property.balance.withdrawal-index') && $this->user instanceof Property){
            return [
                'withdrawal_no'=>$this->withdrawal_no,
                'amount'=>$this->apply_money,
                'time'=>date('Y-m-d',strtotime($this->apply_time)),
                'name'=>$this->user->name,
            ];
        }else if($request->routeIs('property.balance.index')){
            return [
//                'no'=>$this->no,
//                'type'=>$this->type,
//                'type_rename'=>$this->type_rename,
//                'amount'=>$this->amount,
//                'time'=>date('Y-m-d',strtotime($this->time)),
                'amount' => $this->amount,
                'time' => $this->date

            ];
        }

    }
}
