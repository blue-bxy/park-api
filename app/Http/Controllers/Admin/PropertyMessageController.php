<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Admin\PropertyMessageResource;
use App\Models\Users\PropertyMessage;
use Illuminate\Http\Request;

class PropertyMessageController extends BaseController
{

    /**推送消息列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = PropertyMessage::query();

        $query->with('admin','park');

        $per_page = $request->input('per_page');

        $data = $query->search($request)->orderby('id','desc')->paginate($per_page);

        return PropertyMessageResource::collection($data);
    }

    /**
     * 添加推送消息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $model = new PropertyMessage();

        $ret = $model->add($request);

        if(!$ret){
            return $this->responseFailed();
        }

        return $this->responseSuccess();
    }
}
