<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DiscountUserRuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'is_activity_active' => $this->is_activity_active,
            'is_regression_active' => $this->is_regression_active,
            'is_new_user' => $this->is_new_user,
            'is_active' => $this->is_active
        ];
        if ($request->routeIs('*.index')) {
            $data = array_merge($data, [
                'created_at' => $this->created_at->format('Y-m-d H:i') ?? null
            ]);
        } elseif ($request->routeIs('*.show')) {
            $data = array_merge($data, [
                'activity_setting_days' => $this->activity_setting_days,
                'active_days' => $this->active_days,
                'regression_days' => $this->regression_days,
                'desc' => $this->desc
            ]);
        }
        return $data;
    }
}
