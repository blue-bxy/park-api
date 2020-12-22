<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ExcelResource extends JsonResource
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
            'id' => $this->id,
            'excel_name' => $this->excel_name,
            'excel_type' => $this->excel_type,
            'excel_size' => $this->excel_size,
            'create_excel_time' => $this->create_excel_time,
            'load_type_id' => $this->load_type_id,
        ];
    }
}
