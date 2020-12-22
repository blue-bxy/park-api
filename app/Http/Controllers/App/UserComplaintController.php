<?php

namespace App\Http\Controllers\App;

use App\Exceptions\InvalidArgumentException;
use App\Http\Requests\UserComplaintRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use App\Models\Users\UserComplaint;
use App\Http\Resources\App\UserComplaintResource;
use Intervention\Image\Facades\Image;

class UserComplaintController extends BaseController
{
    /**
     * 投诉列表-我的投诉页面首页
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $query = $user->complaints()->getQuery();

        $query->latest();

        $result = $request->input('state');

        // 0 处理中，1已完成
        if (is_numeric($result)) {
            $query->state($result);
        }

        $complaints = $query->paginate();

        return $this->responseData(UserComplaintResource::collection($complaints));
    }

    /**
     * 插入投诉
     *
     * @param UserComplaintRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserComplaintRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $pics = $request->allFiles();

        $file_paths = new Collection();

        collect($pics['img'])->each(function (UploadedFile $file) use ($file_paths) {
            try {
                $filename = filename($file);

                $path = $file->storeAs('evaluate', $filename, 'public');

                if ($path) {
                    $file_paths->push($path);
                }

            } catch (\Exception $exception) {
                throw new InvalidArgumentException('图片存储失败，请稍后重试');
            }
        });

        $user->complaints()->create([
            'content' => $request->input('content'),
            'imgurl'  => $file_paths,
        ]);

        return $this->responseSuccess();
    }

    /**
     * 删除投诉
     *
     * @param UserComplaint $complaint
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(UserComplaint $complaint)
    {
        $this->authorize('own', $complaint);

        $complaint->delete();

        return $this->responseSuccess();
    }
}
