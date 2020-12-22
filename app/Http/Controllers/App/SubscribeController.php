<?php

namespace App\Http\Controllers\App;

use App\Exceptions\PaymentOrderNotFundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\App\SubscribeParkSpaceResource;
use App\Http\Resources\CarportMapResource;
use App\Http\Resources\ParkAreaSpaceResource;
use App\Http\Resources\ParkRateResource;
use App\Http\Resources\ParkSpaceResource;
use App\Models\Dmanger\CarApt;
use App\Models\Dmanger\CarAptOrder;
use App\Models\Dmanger\CarRent;
use App\Models\Parks\Park;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkingLotOpenApply;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkStall;
use App\Models\Payment;
use App\Models\User;
use App\Models\Users\UserCollect;
use App\Models\Users\UserOrder;
use App\Packages\Map\MapServer;
use App\Services\SubscribeService;
use Illuminate\Http\Request;

class SubscribeController extends BaseController
{
    // 停车场名称、定位 查找附近停车场
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $request->validate([
            'latitude' => 'required|string|latitude',
            'longitude' => 'required|string|longitude',
        ]);

        $query = Park::query();

        $park_name = $request->input('park_name');

        // 用户当前位置，经纬度
        $locations = [$request->input('longitude'), $request->input('latitude')];

        // 测试阶段无经纬度要求
        if (!app()->isProduction()) {
            $query->where('id', 11);
        } else {
            $query->geo($locations);
        }

        if ($park_name) {
            $query->where('park_name', 'like', "%{$park_name}%");
        }

        if ($user) {
            $query->addSelect([
                'has_favorite' => UserCollect::query()
                    ->whereColumn('park_id', 'parks.id')
                    ->where('user_id', $user->id)
                    ->selectRaw('count(*)')
            ]);
        }

        // 运营中、开启
        $query->open();

        // 停车场存在预约车位
        $query->whereHas('stall', function ($query) {
            $query->where('order_carport', '>', 0);
        });

        // $query->latest('score'); // 按照评分倒序
        // 停车场收费标准
        $query->selectFee();

        // 可预约车位
        $query->reservedSpaces();

        $parks = $query->limit(30)->get();

        return $this->responseData(SubscribeParkSpaceResource::collection($parks));
    }

    public function show(Request $request, $park_id)
    {
        /** @var User $user */
        $user = $request->user();

        $query = Park::query();

        $query->open();

        /** @var Park $park */
        $park = $query->with("areas")
            ->with('areas.spaces.rental')
            // 车位默认推荐逻辑：业主车位优先、价格低优先
            ->with(['areas.spaces' => function ($query) {
                $time = date('H:i');
                $query->leftJoin('car_rents', 'car_rents.park_space_id', '=', 'park_spaces.id')
                    ->addSelect('rent_type_id', 'rent_price', 'rent_status')
                    ->selectRaw("case when start <= '{$time}' and stop >= '{$time}' then 1 else 0 end is_reserved_type")
                    ->latest('rent_status')
                    ->orderByRaw("field(rent_type_id, 2,1,3)")
                    ->oldest('rent_price');
            }])
            ->with('stall')
            ->withCount('spaces')
            ->findOrFail($park_id);

        $areas = $park->areas;

        $has_favorite = $user ? $user->favorite($park_id) : false;

        $rate = $park->rentals()->whereHas('rate', function ($query) {
            $query->where('is_active', 1);
        })->where('rent_status', 1)->first();

        $data = [
            'total' => $park->spaces_count,
            'has_favorite' => $has_favorite,
            'fee_rules' => $park->stall->fee_string ?? '',
            // 'items' => ParkSpaceResource::collection($spaces),
            'area' => ParkAreaSpaceResource::collection($areas),
            'maps' => new CarportMapResource($park),
            'rates' => $rate ? new ParkRateResource($rate) : []
        ];

        return $this->responseData($data);
    }

    public function getSpaceRate(Request $request, $space_id)
    {
        $space = ParkSpace::query()
            ->has('rental')
            ->with('rental')
            ->primary($space_id)
            ->first();


        if (!$space) {
            return $this->responseNotFound('车位不存在或未设置租金', 40022);
        }

        $rate = $space->getRate();

        return $this->responseData(new ParkRateResource($rate));
    }

    /**
     * 预约
     *
     * @param Request $request
     * @param SubscribeService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiResponseException
     * @throws \Throwable
     */
    public function subscribe(Request $request, SubscribeService $service)
    {
        // 停车场、预约车位、时长、预约车辆
        $request->validate([
            'park_id' => 'required',
            'car_id' => 'required',
            'space_id' => 'sometimes|required',
            'time' => 'required|numeric|min:30',
            'order_no' => 'sometimes|required|string'
        ], [
            'time.min' => '预约时长最少:min分种'
        ]);

        /** @var User $user */
        $user = $request->user();

        $car = $user->cars()->find($car_id = $request->input('car_id'));

        if (!$car) {
            return $this->responseFailed('请选择需要预约的车辆', 40022);
        }

        $park_id = $request->input('park_id');
        $space_id = $request->input('space_id');

        // 检查当前用户是否重复预约
        $service->getPendingPaymentOrder($user, $park_id, $car_id);

        $time = $request->input('time');

        $end_time = now()->addMinutes($time);

        if (!$space = $service->getAvailableParkSpace($park_id, $end_time, $space_id)) {
            return $this->responseFailed('该车位不可选，请更换其他车位', 40015);
        }

        /** @var CarRent $rent */
        $rent = $space->getRate();

        $fees = $rent->rent_price; // 出租车位设置的单价

        // 停车场+区域+车位编号
        $body = "{$space->park_name}  {$space->area_name} {$space->number}";

        // 根据用户提交的预约时间计算金额
        $amount = $rent->getRentalAmount($time);

        if ($amount <= 0) {
            return $this->responseFailed('订单金额非法操作', 40017);
        }

        \DB::beginTransaction();
        try {
            /** @var UserOrder $order */
            $order = $user->orders()->updateOrCreate([
                'order_no' => $request->input('order_no', get_order_no()),
                // 'payment_no' => get_order_no()
            ],[
                'body' => $body,
                'subscribe_amount' => $amount, // 预约金额
                'park_id' => $park_id,
                'user_car_id' => $car_id,
                'car_rent_id' => $rent->getKey()
            ]);

            /** @var CarApt $car_apt */
            $car_apt = $order->carApts()->firstOrNew([]);

            $car_apt->fill([
                'amount' => $amount,
                'total_amount' => $amount,
                'apt_start_time' => now(),
                'apt_end_time' => $end_time,
                'apt_time' => $time,
                'park_id' => $park_id,
                'park_space_id' => $space->getKey(),
                'user_car_id' => $car_id
            ]);

            $car_apt->cacheRate($rent);

            $car_apt->user()->associate($user);
            $car_apt->carRent()->associate($rent);
            $car_apt->save();

            // 锁定车位
            $space->update([
                'status' => 3, // 预约中锁定
            ]);

            /** @var CarAptOrder $apt_order */
            $apt_order = $car_apt->addOrder($amount, $time);

            $order->payment_no = $apt_order->no;

            $order->car_apt_id = $car_apt->getKey();
            $order->expired_at = $apt_order->expired_at;
            $order->save();

            \DB::commit();

            // 下单时间、停车场名称、车位号、停车时长、收费标准、付款金额、倒计时、订单号、type
            return $this->responseData([
                'order_id' => $order->getKey(),
                'order_no' => $order->order_no,
                'paid_no' => $apt_order->no,
                'amount' => $apt_order->amount,
                'has_paid' => $apt_order->has_paid,
                'has_lock' => (bool) $space->locks_count,
                'fees' => $fees, // 10元每小时
                'apt_stop_time' => $time,
                'type' => 'subscribe',
                'body' => $body,
                'expired' => $apt_order->expired_at->timestamp,
                'order_time' => $apt_order->created_at->timestamp // 下单时间
            ]);
        } catch (\Throwable $exception) {
            \DB::rollBack();
            logger($exception);
        }

        return $this->responseFailed('订单创建失败', 40015);
    }

    // 续约
    public function renewal(Request $request, SubscribeService $service)
    {
        // 上次的订单号、续费时长
        $request->validate([
            'order_no' => 'required',
            // 'park_id' => 'required',
            // 'space_id' => 'required',
            // 'apt_time' => 'required'
        ]);

        /** @var User $user */
        $user = $request->user();

        $order_no = $request->input('order_no');

        // 首先确认是否存在预约订单
        /** @var CarApt $apt */
        $apt = $user->subscribe()
            ->with('carRent', 'parkSpace', 'userOrder')
            ->whereHas('userOrder', function ($query) {
                $query->where('status', UserOrder::ORDER_STATE_PAID)
                    ->whereNull('car_in_time') // 未进场
                    ->whereNotNull('paid_at');
            })
            ->whereHas('aptOrder', function ($query) use ($order_no) {
                $query->where('no', $order_no);
            })->firstOr(function () {
                throw new PaymentOrderNotFundException('未发现预约订单，无法续约');
            });

        $time = $request->input('time', 30);

        $end_time = $apt->apt_end_time->addMinutes($time);
        $park_id = $apt->park_id;
        $space_id = $apt->park_space_id;

        $space = $service->getParkSpace($park_id, $end_time, $space_id, true);

        if (!$space) {
            return $this->responseFailed('该车位不可选，请更换其他车位', 40015);
        }
        // 停车场+区域+车位编号
        $body = "{$space->park_name}  {$space->area_name} {$space->number}";

        // 获取续约费用
        $fees = $apt->getParkRateAmount();

        $amount = $service->getRenewalAmount($apt);

        if ($amount <= 0) {
            return $this->responseFailed('订单金额非法操作', 40017);
        }

        \DB::beginTransaction();
        try {
            //创建订单
            $order = $apt->addOrder($amount, 30, true);

            \DB::commit();
            // 下单时间、停车场名称、车位号、停车时长、收费标准、付款金额、倒计时、订单号、type
            return $this->responseData([
                'order_id' => $apt->userOrder->getKey(),
                'order_no' => $apt->userOrder->order_no,
                'paid_no' => $order->no,
                'amount' => $order->amount,
                'has_paid' => $order->has_paid,
                'has_lock' => (bool) $space->locks_count,
                'fees' => $fees, // 10元每小时
                'apt_stop_time' => $time,
                'type' => 'subscribe',
                'body' => $body,
                'expired' => $order->expired_at->timestamp,
                'order_time' => $order->created_at->timestamp // 下单时间
            ]);
        } catch (\Exception $exception) {
            \DB::rollBack();
        }

        return $this->responseFailed('订单创建失败', 40015);
    }

    public function apply(Request $request)
    {
        $request->validate([
           'nickname' => 'required|string',
           'telephone' => 'required|string',
           'village_name' => 'required|string',
           'village_province' => 'sometimes|required|string',
           'village_city' => 'sometimes|required|string',
           'village_country' => 'sometimes|required|string',
           'village_address' => 'required|string',
           'village_telephone' => 'sometimes|required|string',
           'longitude' => 'sometimes|required|longitude',
           'latitude' => 'sometimes|required|latitude',
        ], [
            'nickname.required' => '出租人姓名必须',
            'telephone.required' => '出租人联系方式必须',
            'village_name.required' => '小区名称必须',
            'village_province.required' => '小区省份信息必须',
            'village_city.required' => '小区市级信息必须',
            'village_country.required' => '小区区域信息必须',
            'village_address.required' => '小区地址必须',
        ]);

        /** @var User $user */
        $user = $request->user();

        $exists = ParkingLotOpenApply::query()
            ->where(function ($query) use ($request) {
                $query->orWhere('village_name', $request->input('village_name'))
                    ->orWhere('village_address', $request->input('village_address'));
            })
            ->exists();

        if ($exists) {
            return $this->responseSuccess('该停车场正在受理中，敬请期待！', 40028);
        }

        if ($user && $user->apply()->count() >= 3) {
            return $this->responseFailed('您已提交太多停车场，客户人员忙不过来了~', 40028);
        }

        $apply = new ParkingLotOpenApply();

        $apply->fill($request->all());

        $apply->user()->associate($user);

        $apply->save();

        return $this->responseSuccess('您已成功提交，敬请期待～');
    }

    // 未进场
    public function notEntered(Request $request)
    {
        $data = [
            'order_no' => '',
        ];
        /** @var User $user */
        $user = $request->user();

        $order = $user->orders()->whereNotNull('paid_at')
            ->where('status', UserOrder::ORDER_STATE_PAID)
            ->whereNull('car_in_time') // 未进场
            ->whereHas('carApts', function ($query) {
                $query->where('apt_end_time', '<=', now()->addMinutes(10)); // 提前10分钟
            })
            ->with('carApts')
            ->renewalNotice()
            ->first();

        if ($order) {
            $data = [
                'order_no' => $order->payment_no,
                'expired' => $order->carApts->apt_end_time->timestamp,
                'notice' => "亲～您预约的停车位预计10分钟后到期，是否需要延长预约时间30分钟？"
            ];
        }

        return $this->responseData($data);
    }

    public function cancelRenewalNotice(Request $request)
    {
        $request->validate([
            'no' => 'required'
        ]);

        /** @var User $user */
        $user = $request->user();

        $user->orders()
            ->whereNotNull('paid_at')
            ->where('status', UserOrder::ORDER_STATE_PAID)
            ->whereNull('car_in_time') // 未进场
            ->where('payment_no', $request->input('no'))
            ->update([
                'cancel_renewal_notice' => now(),
                'renewal_notice' => 1
        ]);

        return $this->responseSuccess();
    }

    public function search(Request $request, MapServer $server)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $page = $request->input('page', 1);

        $keyword = $request->input('keyword');

        return $server->keyword($keyword, $page);
    }
}
