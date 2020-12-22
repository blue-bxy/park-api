<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\BrandCollection;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BrandController extends BaseController
{
    /**
     * 品牌列表（设备分开）
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $brands = Brand::query()->get();
        return $this->responseData(BrandCollection::make($brands));
    }


    /**
     * 品牌列表（带型号）
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listWIthModels(Request $request) {
        $brands = Brand::query()
            ->with('models:id,brand_id,name')
            ->where('type', '=', $request->input('type'))
            ->select(['id', 'name'])
            ->get();
        return $this->responseData($brands);
    }

}
