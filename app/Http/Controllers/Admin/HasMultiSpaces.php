<?php


namespace App\Http\Controllers\Admin;


use App\Http\Resources\Admin\ParkSpaceResource;
use App\Models\Parks\ParkSpace;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait HasMultiSpaces {
    /**
     * 车位列表
     * @param $id
     * @return JsonResponse
     */
    public function spaces($id) {
        $device = $this->device->find($id);
        if (empty($device)) {
            return $this->responseNotFound();
        }
        return $this->responseData(ParkSpaceResource::collection($device->spaces));
    }

    /**
     * 添加车位
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function storeSpace(Request $request, $id) {
        $device = $this->device->find($id);
        if (empty($device)) {
            return $this->responseNotFound('请求的设备不存在！');
        }
        $space = ParkSpace::query()->find($request->input('park_space_id'));
        if (empty($space)) {
            return $this->responseNotFound('请求的车位不存在！');
        }

        $spaces = $device->spaces->pluck('id')->toArray();
        if (in_array($space->id, $spaces)) {
            return $this->responseFailed('该车位已绑定！');
        }

        DB::table('park_space_has_devices')
            ->insert([
                'park_space_id' => $request->input('park_space_id'),
                'device_type' => get_class($device),
                'device_id' => $id
            ]);
        return $this->responseSuccess();
    }

    /**
     * 删除设备
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function deleteSpaces(Request $request, $id) {
        $device = $this->device->find($id);
        if (empty($device)) {
            return $this->responseNotFound('请求的设备不存在！');
        }
        $spaces = $request->input('spaces');
        DB::table('park_space_has_devices')
            ->where('device_type', '=', get_class($device))
            ->where('device_id', '=', $id)
            ->whereIn('park_space_id', $spaces)
            ->delete();
        return $this->responseSuccess();
    }
}
