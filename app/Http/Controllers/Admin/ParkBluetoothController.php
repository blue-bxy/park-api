<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\ParkBluetoothExport;
use App\Imports\Admin\ParkBluetoothImport;
use App\Models\Parks\ParkBluetooth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParkBluetoothController extends ParkDeviceController
{
    use HasMultiSpaces;

    public function __construct(ParkBluetooth $bluetooth) {
        parent::__construct($bluetooth);
    }

    /**
     * 导入蓝牙
     * @param Request $request
     * @param ParkBluetoothImport $import
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function import(Request $request, ParkBluetoothImport $import) {
        return $this->responseData($this->innerImport($request, $import));
    }

    /**
     * 导入模板下载
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function importTemplate() {
        return Storage::disk('template')->download('bluetooth.xlsx', 'bluetooth.xlsx');
    }

    /**
     * 导出蓝牙
     * @param Request $request
     * @param ParkBluetoothExport $export
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function export(Request $request, ParkBluetoothExport $export) {
        $excel = $this->innerExport($request, $export, '车场信息-蓝牙表');
        if (empty($excel)) {
            return $this->responseFailed();
        }
        return $this->responseSuccess();
    }
}
