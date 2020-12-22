<?php

namespace App\Http\Controllers\App;

use App\Models\Messages\MessageCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageReportController extends BaseController
{
    /**
     * 短信状态回调
     * @param Request $request
     * @param $gateway
     * @return Response
     */
    public function report(Request $request, $gateway)
    {
        $rawData = $request->all();

        switch ($gateway) {
            case 'qcloud':
                collect($rawData)->each(function ($result) {
                    $data = [
                        'phone' => $result['mobile'],
                        'sid' => $result['sid']
                    ];
                    $value = [
                        'report_status' => $result['report_status'] == 'SUCCESS' ? true : false,
                        'report_time' => $result['user_receive_time'],
                        'report' => json_encode($result)
                    ];
                    MessageCode::updateOrCreate($data, $value);
                });
                break;
            case 'aliyun':
                collect($rawData)->each(function ($result) {
                    $data = [
                        'phone' => $result['phone_number'],
                        'sid' => $result['biz_id']
                    ];
                    $value = [
                        'report_status' => $result['success'],
                        'report_time' => $result['report_time'],
                        'report' => json_encode($result)
                    ];
                    MessageCode::updateOrCreate($data, $value);
                });
                return new Response([
                    'code' => 0,
                    'msg' => '成功'
                ]);
                break;
            default:
                break;
        }

        return \response()->noContent();
    }
}
