<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ParkCameraExport;
use App\Http\Resources\Admin\ParkVirtualSpaceResource;
use App\Imports\Admin\ParkCameraImport;
use App\Models\Parks\ParkCamera;
use App\Models\Parks\ParkVirtualSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParkCameraController extends ParkDeviceController
{
    use HasMultiSpaces;

    public function __construct(ParkCamera $camera) {
        parent::__construct($camera);
    }

    /**
     * 绑定车位列表
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function spaces($id) {
        $camera = ParkCamera::query()->find($id);
        if (empty($camera)) {
            return $this->responseNotFound('请求的摄像头不存在！');
        }
        return $this->responseData(ParkVirtualSpaceResource::collection($camera->virtualSpaces));
    }

    /**
     * 添加车位
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeSpace(Request $request, $id) {
        $camera = ParkCamera::query()->find($id);
        if (empty($camera)) {
            return $this->responseNotFound('请求的摄像头不存在！');
        }
        $space = ParkVirtualSpace::query()->find($request->input('park_virtual_space_id'));
        if (empty($space)) {
            return $this->responseNotFound('请求的车位不存在！');
        }
        $space->park_camera_id = $camera->id;
        $space->save();
        return $this->responseSuccess();
    }

    /**
     * 删除车位
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSpaces(Request $request, $id) {
        $space = ParkVirtualSpace::query()->where('park_camera_id', '=', $id)
            ->find($request->input('park_virtual_space_id'));
        if (empty($space)) {
            return $this->responseNotFound('请求的车位不存在！');
        }
        $space->park_camera_id = 0;
        $space->save();
        return $this->responseSuccess();
    }

    /**
     * 导入摄像头
     * @param Request $request
     * @param ParkCameraImport $import
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function import(Request $request, ParkCameraImport $import) {
        return $this->responseData($this->innerImport($request, $import));
    }

    /**
     * 导入模板下载
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function importTemplate() {
        return Storage::disk('template')->download('camera.xlsx', 'camera.xlsx');
    }

    /**
     * 导出摄像头
     * @param Request $request
     * @param ParkCameraExport $export
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function export(Request $request, ParkCameraExport $export) {
        $excel = $this->innerExport($request, $export, '车场信息-摄像头表');
        if (empty($excel)) {
            return $this->responseFailed();
        }
        return $this->responseSuccess();
    }

}
