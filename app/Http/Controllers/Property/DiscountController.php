<?php

namespace App\Http\Controllers\Property;

use App\Exports\Discount;
use App\Http\Controllers\App\BaseController;
use App\Http\Requests\Property\StoreDiscountRequest;
use App\Http\Resources\Property\DiscountResource;
use App\Models\Coupons\Coupon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DiscountController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 分页数量
        $perPage = $request->input('per_page');

        //查询
        $query = Coupon::query();

        $query->search($request);

        $datas = $query->paginate($perPage);

        return DiscountResource::collection($datas);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try{
            DB::transaction(function () use ($request) {
                $filename = "物业端-优免管理";

                $file_path = get_excel_file_path($filename);

                // 以下为即时导出，队列导出写法不同
                (new Discount($request))->store($file_path);

                $excel_size = \Storage::disk('excel')->size($file_path);

                $model = new \App\Models\ExcelExport([
                    'excel_name' => $filename,
                    'excel_size' => $excel_size,
                    'excel_src' => $file_path,
                    'create_excel_time' => now()
                ]);

                $model->save();
                return $model;
            });

        }catch (QueryException $ex){
            return $this->responseData('','报表生成失败！','1');
        }

        return $this->responseData('','报表生成成功！','0');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDiscountRequest $request)
    {
        $validated = $request->validated();

        $request->merge(['park_id' => $request->user()->park->id,'status'=>1 ]);

        // check no exists
        $exists = Coupon::where('no', $request->input('no'))->exists();
        if ($exists)
        {
            return $this->responseFailed('编号已存在',40022);
        }

        try {
        //  DB::enableQueryLog();
        $coupon = new Coupon($request->all());

        $coupon->publisher()->associate($request->user());

        $coupon->save();


    } catch (QueryException $exception){
        \Log::error($exception);
        //失败
        return $this->responseFailed('新增数据失败',40022);
    }

        // 成功
        return $this->responseSuccess();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = Coupon::query();

        $data=$query->where('id',$id)->latest()->get();

        return DiscountResource::collection($data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
