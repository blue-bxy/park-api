<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ParkAreaRequest;
use App\Http\Resources\Admin\BrandCollection;
use App\Http\Resources\Admin\ParkAreaResource;
use App\Models\Parks\ParkArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ParkAreaController extends BaseController
{
    /**
     * Display a listing of the resource.
     * 停车场区域列表
     * @param   \Illuminate\Http\Request  $request
     * @return  JsonResource
     */
    public function index(Request $request)
    {
        $parkAreas = ParkArea::query()
            ->where('park_id', '=', $request->input('park_id'))
            ->latest('defaulted_at')
            ->paginate($request->input('per_page'));
        return ParkAreaResource::collection($parkAreas);
    }

    /**
     * Store a newly created resource in storage.
     * 新增停车场区域
     * @param ParkAreaRequest $request
     * @return JsonResponse
     */
    public function store(ParkAreaRequest $request)
    {
        ParkArea::query()->create($request->input());
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     * 区域详情
     * @param   int  $id
     * @return  JsonResponse
     */
    public function show($id)
    {
        $area = ParkArea::query()->find($id);
        if (!$area) {
            return $this->responseNotFound();
        }
        return $this->responseData(ParkAreaResource::make($area));
    }

    /**
     * 区域车位发布状态及品牌模板信息
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function getBrand(Request $request, $id) {
        $area = ParkArea::query()
            ->select(['id', 'can_publish_spaces'])
            ->find($id);
        $brand = BrandCollection::make($area->brands);
        $devices = $brand->toArray($request);
        $area = $area->toArray();
        $area['cameras'] = $devices['cameras'];
        $area['bluetooths'] = $devices['bluetooths'];
        $area['blocks'] = $devices['blocks'];
        unset($area['brands']);
        return $this->responseData($area);
    }

    /**
     * 设置车位发布状态及品牌模板信息
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function setBrand(Request $request, $id) {
        $area = ParkArea::query()->find($id);
        $brands = array($request->input('cameras'),
            $request->input('bluetooths'),
            $request->input('blocks'));
        $records = DB::table('park_area_brands')
            ->where('park_area_id', '=', $id)
            ->pluck('brand_id');
        DB::beginTransaction();
        try {
            $area->can_publish_spaces = $request->input('can_publish_spaces');
            $area->save();
            //删除旧记录
            if (!empty($records)) {
                DB::table('park_area_brands')
                    ->where('park_area_id', '=', $id)
                    ->whereIn('brand_id', $records)
                    ->delete();
            }
            //插入新记录
            if (!empty($brands)) {
                $data = array();
                foreach ($brands as $brand) {
                    $data[] = [
                        'park_area_id' => $id,
                        'brand_id' => $brand['id']
                    ];
                }
                DB::table('park_area_brands')->insert($data);
            }
            DB::commit();;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseFailed('更新失败，请稍后重试！');
        }
        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     * 更新区域
     * @param ParkAreaRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(ParkAreaRequest $request, $id)
    {
        $area = ParkArea::query()->find($id);
        if (empty($area)) {
            return $this->responseNotFound();
        }
        $area->fill($request->input());
        $area->save();
        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     * 删除停车场区域
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
//        ParkArea::destroy($id);
//        return $this->responseSuccess();
    }

    public function default(Request $request, ParkArea $area)
    {
        $request->validate([
            'default' => 'required|boolean'
        ]);

        $default = $request->boolean('default', false);

        if ($default) {
            $area->default($default);
        }

        return $this->responseSuccess();
    }
}
