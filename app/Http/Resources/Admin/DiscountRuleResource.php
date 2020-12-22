<?php


namespace App\Http\Resources\Admin;


use Illuminate\Http\Resources\Json\JsonResource;

class DiscountRuleResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'amount' => $this->amount,
            'is_active' => $this->is_active
        ];
        if ($request->routeIs('*.index')) {
            $data = array_merge($data, [
                'publisher' => $this->user->name ?? null,
                'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i') : null,
            ]);
        }
        if ($request->routeIs('*.show')) {
            $data = array_merge($data, [
                'use_scene' => $this->use_scene,
                'value' => $this->value,
                'desc' => $this->desc
            ]);
        }
        return $data;
    }
}
