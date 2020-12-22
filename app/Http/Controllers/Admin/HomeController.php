<?php

namespace App\Http\Controllers\Admin;

use App\Services\CarportDataService;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request, CarportDataService $service)
    {
        // 汇总
        // 运维数据
        // $ops = $service->opsTotal();
        // 运维报修
        // $ops_repair = $service->opsRepair();

        // 财务趋势分析
        $finance = $service->finance();

        // 出租车位分析
        $rent = $service->rent();

        // 停车统计
        $stop = $service->stop();

        // 车流量分析
        $flux = $service->flux();

        // 订单统计
        $order = $service->order();

        // 用户统计
        $user = $service->user();

        // 预约统计
        $apt = $service->reservation();

        // 地图数据
        $map = $service->areaNum();

        return $this->responseData(compact('rent','stop', 'order','apt','user','finance','flux','map'));
    }

    // 地图车场数据
    public function map(Request $request,CarportDataService $service)
    {
        $areaNum = $service->areaNum($request);

        return $this->responseData(compact('areaNum'));
    }
}
