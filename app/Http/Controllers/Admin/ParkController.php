<?php

namespace App\Http\Controllers\Admin;


use App\Exports\ParkInformation;
use App\Http\Requests\ParkRequest;
use App\Http\Resources\Admin\ParkResource;
use App\Models\Customers\ProjectGroup;
use App\Models\Parks\Park;
use App\Models\Regions\City;
use App\Models\Regions\Country;
use App\Models\Regions\Province;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class ParkController extends BaseController
{
    /**
     * 车场列表
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $parks = Park::query()
            ->with(['projectGroup', 'parkStall', 'parkService'])
            ->search($request)
            ->latest()
            ->paginate($request->input('per_page'));
        return ParkResource::collection($parks);
    }

    /**
     * Show the form for creating a new resource.
     *生成报表
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $filename = "车场信息-停车场表";

                $file_path = get_excel_file_path($filename);

                // 以下为即时导出，队列导出写法不同
                (new ParkInformation($request))->store($file_path);

                $excel_size = ceil(\Storage::disk('excel')->size($file_path) / 1024);

                $model = new \App\Models\ExcelExport([
                    'excel_name'        => $filename,
                    'excel_size'        => $excel_size,
                    'excel_src'         => $file_path,
                    'create_excel_time' => now()
                ]);

                $model->save();
                return $model;
            });

        } catch (\Exception $ex) {
            return $this->responseFailed('报表生成失败！', '1');
        }

        return $this->responseSuccess('报表生成成功！');

    }

    /**
     * 数据处理（新增、更新用）
     * @param Request $request
     * @return array
     */
    private function editData(Request $request) {
        $data = $request->input();
        $province = Province::query()
            ->where('province_id', '=', $data['province_id'])
            ->first();
        $city = City::query()
            ->where('city_id', '=', $data['city_id'])
            ->first();
        $county = Country::query()
            ->where('country_id', '=', $data['area_id'])
            ->first();

        return array_merge($data, [
            'park_province' => $province->name,
            'park_city' => $city->name,
            'park_area' => $county->name,
            'property_id' => 0
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ParkRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(ParkRequest $request)
    {
        $data = $this->editData($request);
        $data['unique_code'] = $data['park_number'];

        $park = DB::transaction(function () use ($data) {
            $group = ProjectGroup::query()
                ->firstOrCreate([
                'group_name' => $data['group_name']
            ]);
            $data['project_group_id'] = $group->id;
            $park = Park::query()
                ->create($data);
            $park->stall()->create($data);
            $park->parkService()->create($data);
            return $park;
        });
        if (empty($park)) {
            return $this->responseFailed();
        }
        return $this->responseSuccess();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $park = Park::query()->find($id);
        if (empty($park)) {
            return $this->responseNotFound();
        }
        return $this->responseData(ParkResource::make($park));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ParkRequest $request
     * @param $id
     * @return JsonResponse
     * @throws \Throwable
     */
    public function update(ParkRequest $request, $id)
    {
        $park = Park::query()
            ->find($id);
        $data = $this->editData($request);

        $park = DB::transaction(function () use ($data, $park) {
            $group = ProjectGroup::query()
                ->firstOrCreate([
                'group_name' => $data['group_name']
            ]);
            $data['project_group_id'] = $group->id;
            $park->fill($data)->save();
            $park->stall->fill($data)->save();
            $park->parkService->fill($data)->save();
            return $park;
        });
        if (empty($park)) {
            return $this->responseFailed();
        }
        return $this->responseSuccess();
    }

    public function setProperty(Request $request, $id) {
        $park = Park::query()->find($id);
        if (empty($park)) {
            return $this->responseNotFound();
        }
        $park->property_id = $request->input('property_id');
        $park->save();
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
     * By wjk
     * 停车设置侧边栏菜单
     * @param   Request $request
     * @return  JsonResource|JsonResponse
     */
    public function sidebar(Request $request)
    {
        $parks = Park::query()
            ->where('project_name', 'like', '%'.$request->input('project_name').'%')
            ->with('areas')
            ->paginate($request->input('per_page', 20));
        return ParkResource::collection($parks);
    }

    /**
     * 停车场列表（简化版）
     * @param Request $request
     * @return JsonResponse
     */
    public function simplifiedList(Request $request) {
        $parks = Park::query()->where('park_name', 'like',
            '%'.$request->input('park_name').'%')
            ->with('areas:id,name,park_id')
            ->select(['id', 'park_name'])
            ->limit($request->input('limit', 20))
            ->get();
        return $this->responseData($parks);
    }

}
