<?php

namespace App\Http\Controllers\App;

use App\Exceptions\ApiResponseException;
use App\Http\Requests\RentalRequest;
use App\Http\Resources\App\RentalIncomeBillResouce;
use App\Http\Resources\RentalRecordResource;
use App\Models\Dmanger\CarRent;
use App\Models\Financial\Withdrawal;
use App\Models\User;
use App\Models\Users\ParkingSpaceRentalBill;
use App\Models\Users\UserParkingSpace;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class RentalsController extends BaseController
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $per_page = $request->input('per_page', 30);

        $records = $user->rentals()
            ->with('rent', 'car', 'user')
            ->latest()
            ->paginate($per_page);

        $data = [
            'total' => $records->total(),
            'items' => RentalRecordResource::collection($records)
        ];

        return $this->responseData($data);
    }

    public function store(RentalRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $parking_id = $request->input('parking_id');

        // 验证该停车场允许出租
        $space = $this->getParkingSpaceBy($parking_id);

        /** @var CarRent $rent */
        $rent = $user->rents()->firstOrNew(['user_space_id' => $parking_id], [
            'rent_no' => get_order_no(),
            'rent_num' => $space->number,
            'rent_type_id' => 2, //车主
        ]);

        $files = $request->allFiles();

        $pics = $this->getFilename($files);

        $open = $request->boolean('status');

        $rent->fill([
            'rent_price' => $request->input('price'),
            'start' => $request->input('start_time'),
            'stop' => $request->input('end_time'),
            'pics' => $pics,
            'rent_status' => $open
        ]);

        $rent->parks()->associate($space->park_id);
        $rent->space()->associate($space->park_space_id);

        $rent->save();

        $space->opened_at = $open ? now() : null;

        $space->save();

        return $this->responseSuccess();
    }

    public function update(Request $request, $parking_id)
    {
        $request->validate([
            'open' => 'required|boolean'
        ]);
        // 验证该停车场允许出租
        $space = $this->getParkingSpaceBy($parking_id);

        $this->authorize('own', $space);

        $open = $request->boolean('open');

        $space->opened_at = $open ? now() : null;

        if ($space->rent) {
            $space->rent->rent_status = $open;
        }

        $space->push();

        return $this->responseSuccess('修改成功');
    }

    protected function getParkingSpaceBy($parking_id)
    {
        // 验证该停车场允许出租
        $space = UserParkingSpace::query()->whereHas('park.areas', function ($query) {
            $query->where('can_publish_spaces', true);
        })
            ->with('park')
            ->find($parking_id);

        if (!$space) {
            throw new ApiResponseException('系统暂时不允许出租', 40022);
        }

        // 验证该车位是否可以出租
        if (!$space->hasAllowed()) {
            throw new ApiResponseException('该车位未授权，不能进行出租操作', 40022);
        }

        return $space;
    }

    protected function getFilename($files)
    {
        try {
            $pics = collect(array_get($files, 'pics'))->map(function (UploadedFile $file) {
                $filename = filename($file);

                return $file->storeAs('rentals', $filename, 'public');
            })->filter()->all();

        } catch (\Exception $exception) {
            return $this->responseFailed('图片上传失败', 40025);
        }

        return $pics;
    }

    public function destroy($parking_id)
    {
        //
    }

    /**
     * 收益记录
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function incomeRecord(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $query = $user->rentalIncomeBills()->newQuery();

        $query->latest();

        $items = $query->paginate();

        $data = [
            'total' => $items->total(),
            'rental_amount' => $user->rental_amount,
            'total_rental_amount' => $user->getCacheField('rental_amount.total'),
            'items' => RentalIncomeBillResouce::collection($items)
        ];

        return $this->responseData($data);
    }

    public function withdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            // 'account_number' => 'required|string',
            'account_name' => 'required|string',
            'account' => 'required|string',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (! ($user->rental_amount > 0)) {
            return $this->responseFailed('无可用金额，无法提现', -1);
        }

        $amount = $request->input('amount');

        if ($user->rental_amount < $amount) {
            return $this->responseFailed('您最多可提现'.number_format($user->rental_amount / 100, 2).'元', -1);
        }

        $gateway = $request->input('gateway', 'ali_app');

        // $openid = $user->openid($gateway);

        \DB::beginTransaction();
        try {
            $user->decrementRentalAmount($amount);

            $withdrawal = new Withdrawal([
                'withdrawal_no' => get_order_no(),
                'person_type' => 2,
                'apply_time' => now(),
                'apply_money' => $amount,
                'gateway' => $gateway,
                'account' => $request->input('account'),
                'account_name' => $request->input('account_name'),
            ]);

            $withdrawal->user()->associate($user);

            $withdrawal->save();

            /** @var ParkingSpaceRentalBill $bill */
            $bill = $user->rentalIncomeBills()->newModelInstance([
                'body' => '提现',
                'amount' => $amount,
                // 'park_id' => '',
                'no' => $withdrawal->withdrawal_no,
                'type' => 1, // 减少
                'rental_amount' => $user->rental_amount
            ]);

            $bill->user()->associate($user);
            $bill->order()->associate($withdrawal);
            $bill->save();

            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();

            return $this->responseFailed('提现失败，请重试', 40011);
        }

        return $this->responseSuccess('提现申请成功，我们将在1～3个工作日内完成打款。');
    }
}
