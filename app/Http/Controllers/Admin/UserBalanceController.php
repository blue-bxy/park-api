<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\UserBalanceResource;
use App\Models\Users\UserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserBalanceController extends BaseController
{
    /**
     * 用户流水记录列表
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = UserBalance::query();

        $query->with('order','user');

        //订单管理-充值订单传递至此的user_id
        if($user_id=$request->input('user_id')){
            $query->where('user_id',$user_id);
        }



        $per_page = $request->input('per_page');

        $data = $query->search($request)->orderBy('id','desc')->paginate($per_page);

        return UserBalanceResource::collection($data);
    }

    /**
     * 报表导出
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "数据管理-用户流水记录表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new \App\Exports\Admin\UserBalance($request))->store($file_path);

            $excel_size = \Storage::disk('excel')->size($file_path);

            // 将字节转化成kb
            $excel_size = ceil($excel_size/1024);

            $model = new \App\Models\ExcelExport([
                'excel_name' => $filename,
                'excel_size' => $excel_size,
                'excel_src' => $file_path,
                'create_excel_time' => now()
            ]);

            $model->save();
            return $model;
        });
        if($excel){
            return $this->responseSuccess();
        }else{
            return $this->responseFailed('报表生成失败！','4007');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
