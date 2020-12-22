<?php

namespace App\Http\Controllers\App;

use App\Http\Resources\App\ParkingSpaceResource;
use App\Http\Resources\App\SubscribeParkSpaceResource;
use App\Models\Parks\Park;
use App\Models\User;
use App\Models\Users\UserParkingSpace;
use App\Rules\IDCardNumberValidate;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class UserParkingSpaceController extends BaseController
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $spaces = $user->space()
            ->with('park', 'rent')
            ->paginate();


        $data = ParkingSpaceResource::collection($spaces);

        return $this->responseData($data);
    }

    public function store(Request $request)
    {
        $request->validate([
           'number' => 'required|string',
           'park_id' => 'required',
           'id_card_number' => ['required', 'string', new IDCardNumberValidate()],
           'id_card_name' => 'required|string',
           'contracts' => 'sometimes|required|array|max:3',
           'certificates' => 'required|array|max:3',
        ], [
            'number.required' => '车位编号必须',
            'park_id.required' => '请选择所属小区或商场',
            'contracts.required' => '租赁合同必须',
            'certificates.required' => '车位产权证书必须',
            'certificates.max' => '车位产权证书最大可上传:max张',
            'id_card_number.required' => '身份证号码必填',
            'id_card_name.required' => '身份证姓名必填',
        ]);

        /** @var User $user */
        $user = $request->user();

        $number = $request->input('number');

        $exists = UserParkingSpace::query()->where('number', $number)->exists();

        if ($exists) {
            return $this->responseFailed('该车位已被绑定，请联系管理员', 40022);
        }

        $park_id = $request->input('park_id');

        $callback = function ($query) use ($number) {
            $query->where('number', $number);
        };

        $park = Park::query()->whereHas('spaces', $callback)
            ->with(['spaces' => $callback])
            ->whereHas('areas', function ($query) {
                $query->where('can_publish_spaces', 1);
            })
            ->find($park_id);

        if (!$park) {
            return $this->responseFailed('该车位不存在或暂不支持对外出租', 40022);
        }

        $files = $request->allFiles();

        $contracts = $this->getFilepath(array_get($files, 'contracts'), 'contracts');

        $certificates = $this->getFilepath(array_get($files, 'certificates'), 'certificates');

        $user->space()->create([
            'park_id' => $park_id,
            'park_space_id' => ($spaces = $park->spaces->first()) ? $spaces->id : null,
            'number' => $number,
            'contracts' => $contracts,
            'certificates' => $certificates,
            'id_card_number' => $request->input('id_card_number'),
            'id_card_name' => $request->input('id_card_name'),
            'status' => UserParkingSpace::STATUS_PENDING
        ]);

        return $this->responseSuccess('您已成功提交的车位认证，将在1-3个工作日内完成审核～');
    }

    protected function getFilepath($files, $path)
    {
        try {
            $filepath = collect($files)->map(function (UploadedFile $file) use ($path) {
                $filename = filename($file);

                return $file->storeAs($path, $filename, 'public');
            });
        } catch (\Exception $exception) {
            return $this->responseFailed('证书上传失败，请重试', 40025);
        }

        return $filepath;
    }

    /**
     * 获取合作的停车场
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function park(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
            // 'latitude' => 'required|string|latitude',
            // 'longitude' => 'required|string|longitude',
        ]);

        $query = Park::query();

        $query->open();

        // 允许出租车位
        // $query->whereHas('areas', function ($query) {
        //     $query->where('can_publish_spaces', true);
        // });

        $keyword = $request->input('keyword');

        // 停车场收费标准
        $query->selectFee();

        // 可预约车位
        $query->reservedSpaces();

        $query->where('park_name', 'like', "%$keyword%");

        $query->latest();

        // $query->limit(30);

        $parks = $query->paginate(30);

        return $this->responseData(SubscribeParkSpaceResource::collection($parks));
    }
}
