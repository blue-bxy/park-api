<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ParkGateExport;
use App\Http\Requests\Admin\ParkGateRequest;
use App\Http\Resources\Admin\ParkGateResource;
use App\Models\ExcelExport;
use App\Models\Parks\ParkGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParkGateController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $gates = ParkGate::query()->with('park')
            ->search($request)
            ->paginate($request->input('per_page'));
        return ParkGateResource::collection($gates);
    }

    /**
     * Store a newly created resource in storage.
     * @param ParkGateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ParkGateRequest $request)
    {
        if (ParkGate::query()->where('park_id', '=', $request->input('park_id'))->exists()) {
            return $this->responseFailed('该车场已存在通讯参数，请不要重复设置！');
        }
        ParkGate::query()->create($request->validated());
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $gate = ParkGate::query()->find($id);
        if (empty($gate)) {
            return $this->responseNotFound('请求的记录不存在！');
        }
        return $this->responseData(ParkGateResource::make($gate));
    }

    /**
     * Update the specified resource in storage.
     * @param ParkGateRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ParkGateRequest $request, $id)
    {
        $gate = ParkGate::query()->find($id);
        if (empty($gate)) {
            return $this->responseNotFound('请求的记录不存在！');
        }
        $gate->fill($request->validated());
        $gate->save();
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
     * @param Request $request
     * @param ParkGateExport $export
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function export(Request $request, ParkGateExport $export) {
        $excel = DB::transaction(function () use ($request, $export) {
            activity()->enableLogging();
            $fileName = '车场信息-通讯参数设置表';
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
            return $this->responseFailed('导出失败！');
        }
        return $this->responseSuccess();
    }
}
