<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\ParkCameraGroupResource;
use App\Http\Resources\Admin\ParkDeviceResource;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkCamera;
use App\Models\Parks\ParkCameraGroup;
use Illuminate\Http\Request;

class ParkCameraGroupController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $groups = ParkCameraGroup::query()
            ->with('cameras')
            ->search($request)
            ->paginate($request->input('per_page'));
        return ParkCameraGroupResource::collection($groups);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $area = ParkArea::query()->find($request->input('park_area_id'));
        if (empty($area)) {
            return $this->responseNotFound();
        }
        $count = $area->cameraGroups()->withTrashed()->count();
        $group = new ParkCameraGroup();
        $group->fill($request->input());
        $group->park_id = $area->park_id;
        $group->unique_id = $area->code.($count + 1);
        $group->save();
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return ParkCameraGroupResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $group = ParkCameraGroup::query()->with('cameras')->find($id);
        if (empty($group)) {
            return $this->responseNotFound();
        }
        return $this->responseData(ParkCameraGroupResource::make($group));
    }

    /**
     * 未编组的摄像头列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function camerasWithoutGroup(Request $request) {
        $cameras = ParkCamera::query()->with('brand')
            ->where('park_area_id', '=', $request->input('park_area_id'))
            ->where('group_id', '=', 0)
            ->paginate($request->input('per_page'));
        return ParkDeviceResource::collection($cameras);
    }
    /**
     * 添加摄像头
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeCamera(Request $request, $id) {
        $group = ParkCameraGroup::query()->find($id);
        if (empty($group)) {
            return $this->responseNotFound();
        }
        $camera = ParkCamera::query()->find($request->input('park_camera_id'));
        if (empty($camera)) {
            return $this->responseNotFound();
        }
        $camera->group_id = $group->id;
        $camera->save();
        return $this->responseSuccess();
    }

    /**
     * 删除摄像头
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteCamera(Request $request, $id) {
        $camera = ParkCamera::query()
            ->where('group_id', '=', $id)
            ->find($request->input('park_camera_id'));
        if (empty($camera)) {
            return $this->responseNotFound();
        }
        $camera->group_id = 0;
        $camera->save();
        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $group = ParkCameraGroup::query()->find($id);
        $group->fill($request->input());
        $group->save();
        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $group = ParkCameraGroup::query()->find($id);
        if (empty($group)) {
            return $this->responseNotFound();
        }
        $group->delete();
        return $this->responseSuccess();
    }
}
