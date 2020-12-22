<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use App\Models\Users\Message;
use Illuminate\Http\Request;
use App\Http\Resources\App\UserMessageResource;

class UserMessageController extends BaseController
{
    /**
     * 消息中心首页
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 20);
        /** @var User $user */
        $user = $request->user();
        // 4优惠券、0系统、1行程
        $query = $user->messages()->getQuery();

        $query->whereIn('type', [0,1,4]);

        $query->latest('type')->latest();

        // $messages = $query->groupBy('type')->get();
        //
        // $data = [];
        //
        // $tags = [
        //     4 => 'coupon',
        //     1 => 'order',
        //     0 => 'system'
        // ];
        //
        // foreach ($tags as $key => $tag) {
        //     $item = $messages->where('type', $key)->first();
        //
        //     $data[$tag] = empty($item) ? new \stdClass() : new UserMessageResource($item);
        // }
        $items = $query->forPage($page, $per_page)->get();

        return $this->responseData(UserMessageResource::collection($items));
    }

    public function coupon(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 20);

        $index = $request->input('index');

        /** @var User $user */
        $user = $request->user();

        return $user->messages()->withTrashed()->toSql();

        $query = $user->messages()->getQuery();

        $query->coupon();

        $query->latest()->latest('id');

        if ($index) {
            $query->where('id', '<', $index);
        }

        $messages = $query->forPage($page, $per_page)->get();

        return $this->responseData(UserMessageResource::collection($messages));
    }

    public function system(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 20);

        $index = $request->input('index');

        /** @var User $user */
        $user = $request->user();

        $query = $user->messages()->getQuery();

        $query->system();

        $query->latest()->latest('id');

        if ($index) {
            $query->where('id', '<', $index);
        }

        $messages = $query->forPage($page, $per_page)->get();

        return $this->responseData(UserMessageResource::collection($messages));
    }

    public function order(Request $request)
    {
        $page = $request->input('page', 1);
        $per_page = $request->input('per_page', 20);

        $index = $request->input('index');

        /** @var User $user */
        $user = $request->user();

        $query = $user->messages()->getQuery();

        $query->order();

        $query->latest()->latest('id');

        if ($index) {
            $query->where('id', '<', $index);
        }

        $messages = $query->forPage($page, $per_page)->get();

        return $this->responseData(UserMessageResource::collection($messages));
    }

    /**
     * 消息详情
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $message = $user->messages()->where('id', $id)->first();

        if ($message && !$message->hasRead()) {
            $message->read_time = now();
            $message->save();
        }

        return $this->responseData(new UserMessageResource($message));
    }

    /**
     * 删除消息
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $message = $user->messages()->where('id', $id)->first();

        if ($message) {
            return $this->responseFailed('信息不存在');
        }

        try {
            $message->delete();
            return $this->responseSuccess();
        } catch (\Exception $e) {
            return $this->responseFailed('操作失败，请重试');
        }
    }
}
