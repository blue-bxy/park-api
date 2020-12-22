<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Users\Setting;

class SettingController extends BaseController
{
	/**
     * 用户指南
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function userGuide()
    {
        $guide = settings('user_guide');

        return $this->responseData(compact('guide'));

    }

	/**
     * 关于我们
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function about()
    {
        $about = settings('about');

        return $this->responseData(compact('about'));
    }
}
