<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ParkDevicesExport;
use App\Http\Resources\Admin\ParkDeviceResource;
use App\Imports\Admin\ParkDeviceImport;
use App\Models\ExcelExport;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkDevice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

abstract class ParkDeviceController extends BaseController
{
    protected $device;

    public function __construct(ParkDevice $device) {
        $this->device = $device;
    }

    /**
     * Display a listing of the resource.
     * 设备列表
     * @param  Request $request
     * @return JsonResource
     */
    public function index(Request $request)
    {
        return ParkDeviceResource::collection($this->device
            ->with(['park', 'area', 'brand', 'model'])
            ->search($request)
            ->paginate($request->input('per_page')));
    }

    /**
     * Store a newly created resource in storage.
     * 新增设备
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $device = $this->device->create($request->input());
        if (empty($device)) {
            return $this->responseFailed('添加失败，请稍后重试！');
        }
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     * 设备详情
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $device = $this->device->find($id);
        if (empty($device)) {
            return $this->responseNotFound('请求的设备不存在！');
        }
        return $this->responseData(ParkDeviceResource::make($device));
    }

    /**
     * Update the specified resource in storage.
     * 更新设备信息
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $device = $this->device->find($id);
        if (empty($device)) {
            return $this->responseNotFound('请求的设备不存在！');
        }
        $device->fill($request->toArray())->save();
        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $device = $this->device->find($id);
        if (empty($device)) {
            return $this->responseNotFound('请求的设备不存在！');
        }
        $device->spaces()->detach();
        $device->delete();
        return $this->responseSuccess();
    }

    /**
     * 导入设备
     * @param Request $request
     * @param ParkDeviceImport $import
     * @return array
     * @throws \Throwable
     */
    protected function innerImport(Request $request, ParkDeviceImport $import) {
        $request->validate([
            'upload_file' => 'required|file',
            'park_area_id' => 'required|integer'
        ]);
        $data = Excel::toArray($import, $request->file('upload_file'));
        $area = ParkArea::query()->select(['id', 'park_id'])->find($request->input('park_area_id'));
        $extra = [
            'park_id' => $area->park_id,
            'park_area_id' => $area->id
        ];
        return $import->save($data[0], $extra);
    }

    /**
     * 导出设备
     * @param Request $request
     * @param ParkDevicesExport $export
     * @param string $fileName
     * @return mixed
     * @throws \Throwable
     */
    protected function innerExport(Request $request, ParkDevicesExport $export, string $fileName = null) {
        return DB::transaction(function () use ($request, $export, $fileName) {
            activity()->enableLogging();
            $fileName = $fileName ?? '设备表';
            $path = get_excel_file_path($fileName);
            $export->store($path);
            $size = \Storage::disk('excel')->size($path);
            $size = ceil($size / 1024);
            return ExcelExport::query()->create([
                'excel_name' => $fileName,
                'excel_size' => $size,
                'excel_src' => $path,
                'create_excel_time' => now()
            ]);
        });
    }

}
