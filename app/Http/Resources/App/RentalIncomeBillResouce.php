<?php

namespace App\Http\Resources\App;

use Illuminate\Http\Resources\Json\JsonResource;

class RentalIncomeBillResouce extends JsonResource
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
            'no' => $this->no,
            'amount' => $this->amount,
            'fee' => $this->fee,
            'type' => $this->type,
            'body' => $this->body,
            'time' => $this->created_at->format("Y.m.d H.i")
        ];
    }
}
