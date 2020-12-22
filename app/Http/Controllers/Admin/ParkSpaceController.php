<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ParkSpacesExport;
use App\Http\Resources\Admin\ParkSpaceResource;
use App\Imports\Admin\ParkSpacesImport;
use App\Models\ExcelExport;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkSpace;
use App\Services\ParkSpaceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ParkSpaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResource
     */
    public function index(Request $request)
    {
        return ParkSpaceResource::collection(
            ParkSpace::search($request)
                ->paginate($request->input('per_page'))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $area = ParkArea::query()->find($request->input('park_area_id'));
        if (empty($area)) {
            return $this->responseNotFound();
        }
        $space = ParkSpace::query()->create(array_merge($request->input(), [
            'park_id' => $area->park_id,
            'area_code' => $area->code
        ]));
        if (empty($space)) {
            return $this->responseFailed();
        }
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $space = ParkSpace::query()->find($id);
        if (!$space) {
            return $this->responseNotFound();
        }
        return $this->responseData(ParkSpaceResource::make($space));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param ParkSpaceService $service
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, ParkSpaceService $service, $id) {
        $space = ParkSpace::query()->find($id);
        if (empty($space)) {
            return $this->responseNotFound();
        }
        $space->fill($request->input());
        if (!is_null($isActive = $request->input('is_active'))) {
            if ($isActive) {
                $space->status = $space->status == ParkSpace::STATUS_DISABLED ? ParkSpace::STATUS_UNPUBLISHED : $space->status;
            } else {
                $space->status = ParkSpace::STATUS_DISABLED;
            }
        }
        $space->status = $space->isDirty('type') ? ParkSpace::STATUS_DISABLED : $space->status;
        $service->update($space);
        return $this->responseSuccess();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 一键添加
     * @param Request $request
     * @param ParkSpaceService $service
     * @return JsonResponse
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function multiStore(Request $request, ParkSpaceService $service) {
        $service->autoStore($request->input());
        return $this->responseSuccess();
    }

    /**
     * 导入车位
     * @param Request $request
     * @param ParkSpacesImport $import
     * @return JsonResponse
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function import(Request $request, ParkSpacesImport $import) {
        $request->validate([
            'upload_file' => 'required|file',
            'park_area_id' => 'required'
        ]);
        $data = Excel::toArray($import, $request->file('upload_file'));
        return $this->responseData($import->save($data[0]));
    }

    /**
     * 导入模板下载
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function importTemplate() {
        return Storage::disk('template')->download('space.xlsx', 'space.xlsx');
    }

    /**
     * 导出车位
     * @param Request $request
     * @param ParkSpacesExport $export
     * @return JsonResponse
     * @throws \Throwable
     */
    public function export(Request $request, ParkSpacesExport $export) {
        $excel = DB::transaction(function () use ($request, $export) {
            activity()->enableLogging();
            $fileName = '车场信息-车位表';
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
        if (empty($excel)) {
            return $this->responseFailed();
        }
        return $this->responseSuccess();
    }

}
