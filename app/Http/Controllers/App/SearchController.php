<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserSearchResource;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $searches = $user->searches()->limit(20)->recent()->latest('click_num')->get();

        return $this->responseData(UserSearchResource::collection($searches));
    }

    /**
     * 删除
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $user->searches()->where('id', $id)->delete();

            return $this->responseSuccess('删除成功');
        } catch (\Exception $exception) {
            //
            return $this->responseFailed('删除失败，请重试', 40012);
        }
    }
}
