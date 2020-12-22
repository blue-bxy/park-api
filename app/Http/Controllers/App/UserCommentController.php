<?php

namespace App\Http\Controllers\App;

use App\Exceptions\InvalidArgumentException;
use App\Models\User;
use App\Models\Users\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Requests\UserCommentRequest;

class UserCommentController extends BaseController
{
    /**
     * 插入评论
     *
     * @param UserCommentRequest $request
     * @param UserOrder $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Throwable
     */
    public function store(UserCommentRequest $request, UserOrder $order)
    {
        $this->authorize('comment', $order);

        $pics = $request->allFiles();

        $file_paths = new Collection();

        try {
            collect($pics['img'] ?? [])->each(function ($file) use ($file_paths) {
                $filename = filename($file);

                $path = $file->storeAs('comment', $filename, 'public');

                if ($path) {
                    $file_paths->push($path);
                }
            });
        } catch (\Exception $exception) {
            throw new InvalidArgumentException('图片存储失败，请稍后重试');
        }

        \DB::transaction(function () use ($order, $request, $file_paths) {
            $order->status = UserOrder::ORDER_STATE_COMMENTED;
            $order->commented_at = now();
            $order->save();

            /** @var User $user */
            $user = $request->user();
            $order->comment()->create([
                'user_id' => $user->id,
                'content' => $request->input('content'),
                'park_id' => $order->park_id,
                'imgurl'  => $file_paths,
                'rate'  => $request->input('rate'),
            ]);
        });

        return $this->responseSuccess();
    }
}
