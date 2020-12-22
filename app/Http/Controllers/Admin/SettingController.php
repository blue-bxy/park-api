<?php

namespace App\Http\Controllers\Admin;

use App\Models\Users\Setting;
use Illuminate\Http\Request;

class SettingController extends BaseController
{

    public function index()
    {
        $data = array();

        $data['service_tel'] = settings()->get('service_tel');

        $data['about'] = settings()->get('about');

        $data['user_guide'] = settings()->get('user_guide');

        return $this->responseData($data);
    }

    /**
     * 更新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function save(Request $request)
    {
        $service_tel = $request->input('service_tel');

        settings()->set('service_tel',$service_tel);

        $about = $request->input('about');

        settings()->set('about',$about);

        $user_guide = $request->input('user_guide');

        settings()->set('user_guide',$user_guide);

        return $this->responseSuccess('新增成功！');
   }
}
