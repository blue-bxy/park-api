<?php

namespace App\Http\Controllers\App;

use App\Exceptions\ApiResponseException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\ParkSpaceNotFoundException;
use App\Exceptions\PaymentOrderNotFundException;
use App\Http\Resources\CarportMapResource;
use App\Http\Resources\FindCarResource;
use App\Jobs\CarBeganEntrance;
use App\Models\Dmanger\CarApt;
use App\Models\Parks\ParkSpace;
use App\Models\User;
use App\Packages\Devices\UBer\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Users\UserOrder;
use App\Http\Resources\App\UserOrderResource;
use App\Http\Resources\App\UserOrderDetailResource;

class UserOrderController extends BaseController
{
    /**
     * 订单列表-我的行程首页
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 15);
        /** @var User $user */
        $user = $request->user();

        $query = $user->orders()->getQuery();

        $status = $request->input('status', 'all');

        if (in_array($status, ['all', 'pending', 'finish'])) {
            $query->$status();
        }

        $query->with('parks', 'carStop', 'car', 'carApts');

        $orders = $query->latest()->paginate($per_page);

        $data = [
            'total' => $orders->total(),
            'items' => UserOrderResource::collection($orders)
        ];

        return $this->responseData($data);
    }

    /**
     * show
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $query = $user->orders()->newQuery();

        $query->with('parks', 'carStop', 'car', 'carApts', 'refund');

        $order = $query->find($id);

        $this->authorize('own', $order);

        return $this->responseData(new UserOrderDetailResource($order));
    }

    /**
     * cancel
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'accepted' => 'required|accepted'
        ], [
            'accepted.accepted' => '您必须同意取消订单'
        ]);

        /** @var User $user */
        $user = $request->user();

        /** @var UserOrder $order */
        $order = $user->orders()->whereNull('cancelled_at')
            ->whereId($id)
            ->firstOr(function () {
            throw new ApiResponseException('订单已取消');
        });

        $order->cancel();

        return $this->responseSuccess('订单已取消');
    }

    public function unlock(Request $request, $id, Application $app)
    {
        /** @var User $user */
        $user = $request->user();

        $subscribe = $user->subscribe()
            ->where('user_order_id', $id)
            ->has('lock')
            ->with('lock')
            ->firstOr(function () {
                throw new ApiResponseException('订单不存在或无地锁');
            });

        try {
            $response = $app->park_lock->unlock($subscribe->lock->number);

            logger($response);
            if ($response['status'] && $response['status'] < 0) {
                return $this->responseFailed('车锁解锁失败');
            }

            return $this->responseSuccess();
        } catch (\Exception $exception) {
            return $this->responseFailed('车锁解锁超时，请重试');
        }
    }

    /**
     * 寻车
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function find(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $query = $user->orders()->getQuery();

        $query->whereHas('stop', function ($query) {
            // 未离场
            $query->hasFindCar();
        });

        $query->with( 'car', 'parks');

        // 最新的订单记录取一条
        $order = $query->latest()->first();

        if (!$order) {
            return $this->responseSuccess('未找到停车记录');
        }

        /** @var ParkSpace $space */
        $space = $order->getParkSpace();

        if (!$space) {
            return $this->responseSuccess('未找到车位');
        }

        $order->space = $space;

        // 订单号，停车场id，车位id，停车场名称，入场时间，收费标准，停车场位置,停车场经纬度
        return $this->responseData(new FindCarResource($order));
    }

    /**
     * test 模拟汽车进场
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function entrance(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $order = $user->orders()
            ->whereNotNull('paid_at')
            ->whereNull('car_in_time')
            ->where('id', $id)
            ->firstOr(function () {
               throw new PaymentOrderNotFundException();
            });

        $this->dispatch(new CarBeganEntrance([
            'car_number' => $order->car->car_number,
            'park_id' => $order->park_id,
        ]));

        return $this->responseSuccess();
    }

    /**
     * test 模拟汽车停车
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function stop(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $order = $user->orders()
            ->whereNotNull('paid_at')
            ->whereNotNull('car_in_time')
            ->whereNull('car_stop_time')
            ->where('id', $id)
            ->firstOr(function () {
                throw new PaymentOrderNotFundException();
            });

        $order->car_stop_time = now();
        $order->stop->car_stop_time = now();
        $order->stop->park_space_id = $order->park_space_id;

        $order->push();

        return $this->responseSuccess();
    }

    /**
     * test 模拟离场
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function out(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $order = $user->orders()
            ->whereNotNull('paid_at')
            ->whereNotNull('car_in_time')
            ->whereNotNull('car_stop_time')
            ->whereNull('car_out_time')
            ->where('id', $id)
            ->firstOr(function () {
                throw new PaymentOrderNotFundException();
            });

        $order->finish(now(), now()->diffInMinutes($order->car_in_time));

        return $this->responseSuccess();
    }

    /**
     * 结束寻车流程
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function exit(Request $request, $id)
    {
        /** @var User $user */
        $user = $request->user();

        $order = $user->orders()
            ->whereHas('stop', function ($query) {
                $query->hasFindCar();
            })
            ->with('stop')
            ->where('id', $id)
            ->first();

        if (!$order) {
            return $this->responseSuccess();
        }

        // 结束寻车流程
        $order->stop->has_find_car = true;

        $order->push();

        return $this->responseSuccess();
    }

    /**
     * 重新分配车位
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reallocate(Request $request)
    {
        // 地图SDK检测到蓝牙信标，给App发送到达通知，获取最新的室内地图数据，
        // 如果车位是有锁继续导航，反之检查车位是否被使用，若已使用重新分配车位
        $request->validate([
            'space_id' => 'required_without:map_space_id',
            'map_space_id' => 'required_without:space_id'
        ]);

        $reallocate = false;

        $space_id = $request->input('space_id');

        $map_space_id = $request->input('map_space_id');

        $space = ParkSpace::query()
            ->with('area')
            ->withCount('locks') // 地锁数量
            ->when($space_id, function ($query) use ($space_id) {
                $query->where('id', $space_id);
            })->when($map_space_id, function ($query) use ($map_space_id) {
                $query->where('map_unique_id', $map_space_id);
            })->firstOr(function () {
                throw new ParkSpaceNotFoundException();
            });

        if (!in_array($space->area->manufacturing_mode, [2,3])) {
            return $this->responseFailed('暂不支持室内导航，敬请期待', 40022);
        }

        // 当车位无地锁且被使用情况下 重新分配车位
        if ($space->locks_count == 0 && $space->is_stop) {
            /** @var Builder $query */
            $query = $space->park->spaces()->getQuery();

            /** @var ParkSpace $space */
            $space = $query->where('status', 1)
                ->withCount('locks')
                ->where('is_stop', false)
                ->inRandomOrder()
                ->first();

            $reallocate = true;
        }

        if (!$space) {
            return $this->responseFailed('目前无空余车位信息，请联系现场工作人员引导', 30100);
        }

        return $this->responseData([
            'space_id' => $space->getKey(),
            'map_space_id' => $space->mapSpaceId(),
            'has_lock' => (bool) $space->locks_count,
            'reallocate' => $reallocate // 是否重新分配
        ]);
    }
}
