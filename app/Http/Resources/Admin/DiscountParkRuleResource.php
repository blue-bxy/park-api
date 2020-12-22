<?php


namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class DiscountParkRuleResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'park_property' => $this->park_property,
            'is_active' => $this->is_active
        ];
        if ($request->routeIs('*.index')) {
            $data = array_merge($data, [
                'publisher' => $this->user->name ?? null,
                'created_at'=>$this->created_at->format('Y-m-d H:i')
            ]);
        } elseif ($request->routeIs('*.show')) {
            $data = array_merge($data, [
                'province_id' => $this->province_id,
                'province' => $this->province->name ?? null,
                'city_id' => $this->city_id,
                'city' => $this->city->name ?? null,
                'district_id' => $this->district_id,
                'district' => $this->district->name ?? null,
                'desc' => $this->desc
            ]);
        }
        return $data;
    }
}
