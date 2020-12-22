<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentGatewayResource extends JsonResource
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
            'name'     => $this->gateway_name,
            'desc'     => $this->desc,
            'icon'     => $this->cover,
            'gateway'  => $this->gateway,
            'maxMoney' => $this->max_money,
        ];
    }
}
