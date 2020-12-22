<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class BalanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
		var_dump($request);die;
        return [
			'user_balance' => $this->balance,
			'payment_logs' => $this->whenPivotLoadedAs('user_payment_logs','orders', function () {
				return [
					'pack_name' => $this->orders->parks->pack_name,
					'created_at' => $this->orders->created_at,
					'money_amount' => $this->user_payment_logs->money_amount,
					'account_type' => $this->user_payment_logs->account_type,
					'business_type' => $this->user_payment_logs->business_type,
				];
			}),
		];
    }
}
