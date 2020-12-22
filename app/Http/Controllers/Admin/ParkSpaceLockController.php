<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ParkSpaceLockExport;
use App\Http\Resources\Admin\ParkDeviceResource;
use App\Imports\Admin\ParkSpaceLockImport;
use App\Models\Parks\ParkSpaceLock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParkSpaceLockController extends ParkDeviceController
{
    public function __construct(ParkSpaceLock $device) {
        parent::__construct($device);
    }

    /**
     * 地锁列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function index(Request $request) {
        return ParkDeviceResource::collection($this->device
            ->with(['park', 'area', 'brand', 'model', 'space'])
            ->search($request)
            ->paginate($request->input('per_page')));
    }

    /**
     * 导入地锁
     * @param Request $request
     * @param ParkSpaceLockImport $import
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function import(Request $request, ParkSpaceLockImport $import) {
        return $this->responseData($this->innerImport($request, $import));
    }

    /**
     * 导入模板下载
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function importTemplate() {
        return Storage::disk('template')->download('lock.xlsx', 'lock.xlsx');
    }

    /**
     * 导出地锁
     * @param Request $request
     * @param ParkSpaceLockExport $export
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function export(Request $request, ParkSpaceLockExport $export) {
        $excel = $this->innerExport($request, $export, '车场信息-地锁表');
        if (empty($excel)) {
            return $this->responseFailed();
        }
        return $this->responseSuccess();
    }

    /**
     * 地锁删除
     * @param int $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id) {
        $lock = ParkSpaceLock::query()->find($id);
        if (empty($lock)) {
            return $this->responseNotFound('请求的地锁不存在！');
        }
        $lock->delete();
        return $this->responseSuccess();
    }
}
