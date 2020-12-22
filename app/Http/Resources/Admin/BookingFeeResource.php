<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingFeeResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $status = [1 => '停用',2 => '启用'];

        if($request->routeIs('admin.booking-fee.show') || $request->routeIs('admin.booking-fee.edit')){
            $data=[
                'id' => $this->id,
                'park_id' => $this->park_id,
                'status' => $this->status,
                'apt'=>$this->apt,
                'stop'=>$this->stop,
                'user' => $this->user->name,
                'updated_at' => $this->updated_at->format('Y-m-d H:i')
            ];
        }else{
            $data= [
                'id'=>$this->id,
                'park_name' => $this->park->project_name ?? null,
                'user' => $this->user->name,
                'status' => $status[$this->status],
                'updated_at' => $this->updated_at->format('Y-m-d H:i')
            ];
        }
        return $data;
    }
}
