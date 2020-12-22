<?php

namespace App\Http\Controllers\Admin;

use App\Events\Orders\Finish;
use App\Exports\DmangerCartApt;
use App\Exports\ExcelExport;
use App\Http\Resources\Admin\AptInfoResource;
use App\Http\Resources\Admin\CarAptOrderResource;
use App\Http\Resources\Admin\CarAptResource;
use App\Http\Resources\Admin\ParkIncomeResource;
use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Dmanger\CarRent;
use App\Models\Dmanger\ParkIncome;
use App\Models\Financial\Withdrawal;
use App\Models\Parks\Park;
use App\Models\Users\UserCar;
use App\Models\Users\UserDevice;
use App\Models\Users\UserOrder;
use App\Packages\JPush\JPushMessage;
use function foo\func;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
/**
 * 预约数据的控制器
 */
class  CarAptController extends BaseController
{
    /**
     * 显示预约数据的查询页面
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        // 查询
        $query = CarApt::query();

        $query->with('orders', 'user', 'userCar', 'parks', 'carRent','parkSpace','divide');

        $query->addSelect([
            'order_no' => UserOrder::select('order_no')
                ->limit(1)
                ->whereColumn('user_orders.id', 'car_apts.user_order_id')
        ]);
        //订单管理-退款查看详情传递至此的order_id
        if($order_id=$request->input('order_id')){
            $apt_id=CarAptOrder::where('id',$order_id)->pluck('car_apt_id')[0];
            $query->where('id',$apt_id);
        }

        //提现管理-物业提现-查看详情传递至此的withdrawal_id
        if($withdrawal_id=$request->input('withdrawal_id')){
            $rent_type_id=$rent_type=$request->input('rent_type')?2:1;
            $withdrawal = Withdrawal::find($withdrawal_id);
            $apply_time = $withdrawal['apply_time'];
            $withdrawals = Withdrawal::where('park_id', $withdrawal['park_id'])
                ->where('apply_time', '<', $apply_time)->orderBy('apply_time', 'desc')->get();

            $query->join('car_rents',function ($join) use ($query,$rent_type_id){
                $join->on('car_apts.car_rent_id','=','car_rents.id');
                $query->where('car_rents.rent_type_id',$rent_type_id);
            });

            $query->where('car_apts.park_id', $withdrawal['park_id']);

            if(count($withdrawals) == 0){
                $query->where('apt_end_time', '<=',$apply_time);
            }else{
                $last_apply_time = $withdrawals[0]->apply_time;
                $query->whereBetween('apt_end_time',
                    [$last_apply_time, $apply_time]);
            }
        }

        $per_page = $request->input('per_page');

        $data = $query->search($request)->orderby('id','desc')->paginate($per_page);

        return CarAptOrderResource::collection($data);
    }

    /**
     * 报表的生成
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $excel = DB::transaction(function () use ($request) {
            activity()->enableLogging(); // 开启记录操作日志

            $filename = "数据管理-预约数据表";

            $file_path = get_excel_file_path($filename);

            // 以下为即时导出，队列导出写法不同
            (new DmangerCartApt($request))->store($file_path);

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
     * 添加post提交数据到预约表中
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->input('user_id');

        // $user_device = UserDevice::query()->where('user_id',$user_id)->first();

        // $jpush_id = $user_device->jpush_id;

        $j_msg = new JPushMessage();

        $data = $j_msg->platform( 'all')
            // ->allAudience()
            // ->audience('registration_id',[$jpush_id])
            ->audience('alias',[$user_id])
            // ->cid($message->no)
            ->alert('提现处理成功')
            ->toArray();

        return app('jpush.push')->send($data);
    }

    /**
     * 查看明细
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 满足进行查询
        $query = UserOrder::query();

        $query->where('car_apt_id',$id);

        $query->with(['parks','carStop','carApts','car']);

        $parkIncome = $query->paginate();

        return ParkIncomeResource::collection($parkIncome);
    }

    /**
     * 显示预约数据的修改
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*
        // 获取指定id的数据
        $data = (CarApt::where("id",$id)->first())->toArray();
        return view('admin.carapt.edit',compact($data));*/
    }

    /**
     * 处理预约数据的修改
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editData(Request $request)
    {
        /* $data = $request->post();
        // 随便模拟的数据
        $data = [
        'id' => 2,
        'apt_price' => 1700
        ];
        $res = CarApt::where('id',$data['id'])->update($data);
        if(!$res){
            return '修改车位预约数据失败！';
        }
        return '修改车位预约数据成功！';*/
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
     * 软删除指定的预约数据
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = CarApt::find($id);
        $res = $model->delete($id);
        if(!$res){
            return '删除失败！';
        }
        return '删除成功！';
    }

    public function test($order_id)
    {


    }
}
