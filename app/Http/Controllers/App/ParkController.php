<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Http\Resources\App\SubscribeParkSpaceResource;
use App\Http\Resources\ParkCommentResource;
use App\Http\Resources\ParkResource;
use App\Models\Parks\Park;
use App\Models\User;
use Illuminate\Http\Request;

class ParkController extends BaseController
{
    public function index(Request $request)
    {
        $request->validate([
            'keyword' => 'sometimes|required|string'
        ]);

        $query = Park::query();

        $query->open();

        // 允许出租车位
        $query->whereHas('areas', function ($query) {
            $query->where('can_publish_spaces', true);
        });

        if ($keyword = $request->input('keyword')) {
            $query->where('park_name', 'like', "%$keyword%");
        }

        $query->latest();

        $parks = $query->paginate(30);

        $data = [
            'total' => $parks->total(),
            'items' => ParkResource::collection($parks)
        ];

        return $this->responseData($data);
    }

    /**
     * 周边停车场
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function around(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        // 周边停车场
        $request->validate([
            // 'keyword' => 'required|string',
            'latitude' => 'required|string|latitude',
            'longitude' => 'required|string|longitude',
        ]);

        $query = Park::query();

        $query->open();

        $query->with('stall');
        // 允许出租车位
        $query->whereHas('areas', function ($query) {
            $query->where('can_publish_spaces', true);
        });

        $long = $request->input('longitude');
        $lat = $request->input('latitude');

        if ($long && $lat) {
            $locations = [$long, $lat];
            $query->geo($locations);
        }

        // 停车场收费标准
        $query->selectFee();

        // 可预约车位
        $query->reservedSpaces();

        $query->latest();

        // $query->limit(30);

        $parks = $query->paginate(30);

        // 添加历史记录
        if ($user && $keyword = $request->input('keyword')) {
            $user->searches()->updateOrCreate([
                'keyword' => $keyword
            ], [
                'latitude' => $lat,
                'longitude' => $long,
                'click_num' => \DB::raw("click_num + 1")
            ]);
        }

        return $this->responseData(SubscribeParkSpaceResource::collection($parks));
    }

    /**
     * 收藏
     *
     * @param Request $request
     * @param $park
     * @return \Illuminate\Http\JsonResponse
     */
    public function favorite(Request $request, $park)
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $collect = $user->collect()->withTrashed()->firstOrNew([
                'park_id' => $park
            ]);

            $collect->trashed() ? $collect->restore() : $collect->save();

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            return $this->responseFailed('操作错误', 40011);
        }
    }

    /**
     * 取消收藏
     *
     * @param Request $request
     * @param $park
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFavorite(Request $request, $park)
    {
        /** @var User $user */
        $user = $request->user();

        try {
            $result = $user->collect()->where('park_id', $park)->delete();

            return $this->responseSuccess();
        } catch (\Exception $e) {
            //
        }

        return $this->responseFailed('操作错误，请重试', 40012);
    }

    /**
     * 评价列表
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function comments(Request $request, $id)
    {
        /** @var Park $park */
        $park = Park::query()->find($id);

        $comments = $park->comments()
            ->with('user')
            ->where('suggestion', 'pass')
            ->where('audit_status', 2)
            ->where('is_display', true)
            ->latest()
            ->paginate();

        // 名称、评价星级、评价列表
        return $this->responseData([
            'park_name' => $park->park_name,
            'park_id' => $park->getKey(),
            'score' => $park->score,
            'list' => ParkCommentResource::collection($comments)
        ]);

    }
}
