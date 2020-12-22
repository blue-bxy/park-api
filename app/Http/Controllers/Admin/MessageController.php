<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\MessageResource;
use App\Models\Users\Message;
use Illuminate\Http\Request;


class MessageController extends BaseController
{
    /**
     * 推送消息列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $query = Message::query();
        // 推送的类型，物业、业主和全部
//        $sent_type = $request->input('send_type');

        $query->with('admin');

//        $query->where('send_type',$sent_type);

        $per_page = $request->input('per_page');

        $data = $query->search($request)->orderby('id','desc')->paginate($per_page);

        return MessageResource::collection($data);
    }

    /**
     * 消息推送的添加
     * @param Request $request
     */
    public function create(Request $request)
    {
        $model = new \App\Models\Users\Message();

        $ret = $model->add($request);

        if(!$ret){
            return $this->responseFailed();
        }

        return $this->responseSuccess();
    }
}
