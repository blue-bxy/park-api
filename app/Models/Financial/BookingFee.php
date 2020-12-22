<?php

namespace App\Models\Financial;

use App\Exceptions\ApiResponseException;
use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingFee extends EloquentModel
{

    use SoftDeletes;

    protected $fillable = ['park_id','apt','stop', 'status','scope', 'user_id'];

    const OWNER_PUBLISH = 1;

    const PROPERTY_PUBLISH = 2;

    protected $casts = [
        'scope' => 'array',
        'apt' => 'array',
        'stop' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class);
    }

    public function park()
    {
        return $this->belongsTo(Park::class);
    }

    /**
     * 获取业主结算标准
     *
     * @param int $amount
     * @return int
     */
    public static function getOwnerFee($amount)
    {
        $fees = static::getFees(1);

        $fee = 0;
        // 百分比
        if ($fees->owner && empty($fees->scope)) {
            $owner = (int) $fees->owner;

            $fee = (int) ($owner * $amount/100);
        }

        // scope 区间值
        // foreach ($fees->scope as $scope) {
        //
        // }

        return $fee;
    }

    public static function getFees($park_id)
    {
        return static::query()->where('park_id', $park_id)
            ->where('status', 2)
            ->firstOr(function () {
                throw new ApiResponseException('无法获取收费细则，请联系管理员', 40022);
            });
    }

    /**
     * 获取表单数据
     * @param $request
     * @return array
     */
    public function data($request)
    {
        $data = array();
        $apt1 = array();
        $apt2 = array();
        $stop1 = array();
        $stop2 = array();

        if($park_id = $request->input('park_id')){
            $data['park_id'] = $park_id;
        }

        if($status = $request->input('status')){
            $data['status'] = $status;
        }

        $data['user_id'] = ($request->user())->id;

        // 预约
        // 物业
        if($pro_plat_apt = $request->input('pro_plat_apt')){
            $apt1['platfotm'] = $pro_plat_apt;
        }else{
            $apt1['platfotm'] = 0;
        }

        if($pro_park_apt = $request->input('pro_park_apt')){
            $apt1['park'] = $pro_park_apt;
        }else{
            $apt1['park'] = 0;
        }

        if($pro_owner_apt = $request->input('pro_owner_apt')){
            $apt1['owner'] = $pro_owner_apt;
        }else{
            $apt1['owner'] = 0;
        }

            // 判断传递过来的比例相加是否等于100，如果不等于报错
        if(($pro_plat_apt + $pro_park_apt + $pro_owner_apt) != 100){
            return false;
        };

        $apt1['type'] = BookingFee::PROPERTY_PUBLISH;

        // 业主
        if($per_plat_apt = $request->input('per_plat_apt')){
            $apt2['platfotm'] = $per_plat_apt;
        }else{
            $apt2['platfotm'] = 0;
        }

        if($per_park_apt = $request->input('per_park_apt')){
            $apt2['park'] = $per_park_apt;
        }else{
            $apt2['park'] = 0;
        }

        if($per_owner_apt = $request->input('per_owner_apt')){
            $apt2['owner'] = $per_owner_apt;
        }else{
            $apt2['owner'] = 0;
        }

        // 判断传递过来的比例相加是否等于100，如果不等于报错
        if(($per_plat_apt + $per_park_apt + $per_owner_apt) != 100){
            return false;
        };

        $apt2['type'] = BookingFee::OWNER_PUBLISH;

        $data['apt'] = [$apt2,$apt1];

        // 停车分成费率
        // 物业
        if($pro_plat_stop = $request->input('pro_plat_stop')){
            $stop1['platfotm'] = $pro_plat_stop;
        }else{
            $stop1['platfotm'] = 0;
        }

        if($pro_park_stop= $request->input('pro_park_stop')){
            $stop1['park'] = $pro_park_stop;
        }else{
            $stop1['park'] = 0;
        }

        if($pro_owner_stop = $request->input('pro_owner_stop')){
            $stop1['owner'] = $pro_owner_stop;
        }else{
            $stop1['owner'] = 0;
        }

        // 判断传递过来的比例相加是否等于100，如果不等于报错
        if(($pro_plat_stop + $pro_park_stop + $pro_owner_stop) != 100){
            return false;
        };

        $stop1['type'] = BookingFee::PROPERTY_PUBLISH;

        //业主
        if($per_plat_stop = $request->input('per_plat_stop')){
            $stop2['platfotm'] = $per_plat_stop;
        }else{
            $stop2['platfotm'] = 0;
        }

        if($per_park_stop= $request->input('per_park_stop')){
            $stop2['park'] = $per_park_stop;
        }else{
            $stop2['park'] = 0;
        }

        if($per_owner_stop = $request->input('per_owner_stop')){
            $stop2['owner'] = $per_owner_stop;
        }else{
            $stop2['owner'] = 0;
        }

        // 判断传递过来的比例相加是否等于100，如果不等于报错
        if(($per_plat_stop + $per_park_stop + $per_owner_stop) != 100){
            return false;
        };

        $stop2['type'] = BookingFee::OWNER_PUBLISH;

        $data['stop'] = [$stop2,$stop1];

        return $data;
    }
}
