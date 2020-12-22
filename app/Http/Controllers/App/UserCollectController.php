<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\App\UserCollectResource;

class UserCollectController extends BaseController
{
	/**
     * 我的收藏首页
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $collects = $user->collect()->with('parks.stall')->paginate();

		return $this->responseData(UserCollectResource::collection($collects));
    }

	/**
     * 添加收藏
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

		$request->validate([
            'park_id' => 'required|integer'
        ]);

		$result = $user->collect()->updateOrCreate([
		    'park_id' => $request->input('park_id')
        ]);

        if($result){
            return $this->responseSuccess('添加成功');
        }

        return $this->responseFailed('添加失败');

    }

    /**
     * 删除收藏
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $result = $user->collect()->where('id', $id)->delete();

        if($result){
            return $this->responseSuccess('删除成功');
        }

        return $this->responseFailed('删除失败');
    }
}
