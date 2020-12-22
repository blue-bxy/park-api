<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BrandCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'cameras' => array(),
            'bluetooths' => array(),
            'blocks' => array()
        ];
        foreach ($this->collection as $brand) {
            switch ($brand->type) {
                case 1:
                    $data['cameras'][] = [
                        'id' => $brand->id,
                        'name' => $brand->name
                    ];
                break;
                case 2:
                    $data['blocks'][] = [
                        'id' => $brand->id,
                        'name' => $brand->name
                    ];
                break;
                case 3:
                    $data['bluetooths'][] = [
                        'id' => $brand->id,
                        'name' => $brand->name
                    ];
                break;
                default:    break;
            }
        }
        if ($request->routeIs('admin.park_area.get_brand')) {
            $data['cameras'] = $data['cameras'][0] ?? array();
            $data['bluetooths'] = $data['bluetooths'][0] ?? array();
            $data['blocks'] = $data['blocks'][0] ?? array();
        }
        return $data;
    }
}
