<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ParkDeviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->routeIs('admin.park_camera_groups.*')) {
            return [
                'id' => $this->id,
                'number' => $this->number,
                'ip' => $this->ip,
                'status' => $this->status,
                'brand_id' => $this->brand_id,
                'brand_name' => $this->brand->name ?? null,
            ];
        }
        $data = [
            'id' => $this->id,
            'number' => $this->number,
            'brand_id' => $this->brand_id,
            'brand_name' => $this->brand->name ?? null,
            'model_id' => $this->brand_model_id,
            'model_name' => $this->model->name ?? null,
            'ip' => $this->ip,
            'protocol' => $this->protocol,
            'gateway' => $this->gateway,
            'electric' => is_null($this->electric) ? null : $this->electric.'%',
            'status' => $this->status,
            'network_status' => $this->network_status,
            'error' => $this->error,
            'remark' => $this->remark,
            'park_id' => $this->park_id,
            'project_name' => $this->park->project_name ?? null,
            'park_area_id' => $this->park_area_id,
            'park_area_name' => $this->area->name ?? null,
        ];
        if ($request->routeIs('admin.park_cameras.*')) {
            $data['monitor_type'] = $this->monitor_type;
        }
        if ($request->routeIs('admin.park_space_locks.*')) {
            $data['park_space_id'] = $this->park_space_id;
            $data['park_space_number'] = $this->space->number ?? null;
        }
        return $data;
    }

}
