<?php


namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class RegionResource extends JsonResource
{
    public function toArray($request)
    {

       $data = [];
       if ($request->routeIs('admin.province')){
            $data = [
                'province' => $this->name,
                'province_id' => $this->province_id,
            ];
       } elseif ($request->routeIs('admin.city')){
           $data = [
                'city' => $this->name,
               'city_id' => $this->city_id,
               'province_id' => $this->province_id,
           ];
       } elseif ($request->routeIs('admin.country')){
           $data = [
                'area' => $this->name,
               'city_id' => $this->city_id,
               'country_id' => $this->country_id,
           ];
       }
       return $data;
    }
}
