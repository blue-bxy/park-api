<?php

namespace App\Http\Controllers\Property;
use App\Services\SummaryOrderService;

class AptOrderDayController extends BaseController
{

    /**
     * 统计前一天的车场预约单的正常结算收入和退款
     * @param SummaryOrderService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function summary(SummaryOrderService $service){
        $result=$service->summary();
        if($result){
            return $this->responseSuccess('统计完成');
        }else{
            return $this->responseFailed('统计失败',1);
        }
    }
}
