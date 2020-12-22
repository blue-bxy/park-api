<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DmangerCarRent;
use App\Http\Resources\Admin\CarRentResource;
use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\Park;
use App\Models\Users\UserParkingSpace;
use Illuminate\Http\Request;
use App\Models\ExcelExport;
use Illuminate\Support\Facades\DB;

class CarRentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = CarRent::query();
//        $query->with('parks', 'orders','user','carApt','carApt.divide','carApt.userOrder','carApt.userOrder.stop.divide');
        $query->with('parks','user','apt','carApt.userOrder.divide');

        $query->where('user_type','App\Models\User');

        $per_page = $request->input('per_page');

        $car_rent = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        return CarRentResource::collection($car_rent);
    }

    public function export(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志
            $filename = "数据管理-出租车位表";

            $file_path = get_excel_file_path($filename);

//            $park_id = 0;
//            if ($park_name = $request->input('park_name')) {
//                $park_id = Park::where('park_name', $park_name)->pluck('id');
//            }
            // 以下为即时导出，队列导出写法不同
            (new DmangerCarRent($request))->store($file_path);

            $excel_size = \Storage::disk('excel')->size($file_path);

            // 将字节转化成kb
            $excel_size = ceil($excel_size/1024);

            $model = new ExcelExport([
                'excel_name'        => $filename,
                'excel_size'        => $excel_size,
                'excel_src'         => $file_path,
                'create_excel_time' => now()
            ]);

            $model->save();
            return $model;
        });

        return $this->responseSuccess();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        DB::transaction(function () use ($request,$id) {

            activity()->enableLogging(); // 开启记录操作日志

            $rent = CarRent::find($id);

            $user_space_id = $rent->user_space_id;

            // 修改状态
            $rent->rent_status = $request->boolean('rent_status', false);

            $rent->save();

            // 同事修改APP中的状态
            $user_space = UserParkingSpace::find($user_space_id);

            if($rent->rent_status){
                $user_space->opened_at = now();
            }else{
                $user_space->opened_at = null;
            }

            $user_space->save();

        });

        return $this->responseSuccess();
    }
}
