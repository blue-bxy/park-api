<?php

namespace App\Http\Resources\Admin;

use App\Models\Financial\ParkingFee;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkEarningsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        $park_fee=ParkingFee::where('park_id',$this->park_id)->pluck('fee')[0]??null;
//        $income=number_format($this->income / 100, 2);
//        $fee=$income*($park_fee/100);
//        return [
//            'park_id'=>$this->parks->id,
//            'park_name'=>$this->parks->project_name??null,
//            'income'=>$income,
//            'date'=>$this->finished_at->format('Y-m-d'),
//            'fee'=>$fee,
//        ];

        return [
            'id' => $this->id,
            'park_id' => $this->park_id,
            'park_name' => $this->park->project_name,
            'income' => $this->amount,
            'fee' => $this->platform_fee,
            'date' => $this->date
        ];
    }
}
