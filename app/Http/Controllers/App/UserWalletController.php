<?php

namespace App\Http\Controllers\App;

use App\Http\Resources\UserBalanceRecordResouce;
use App\Packages\Payments\Config;
use App\Rules\PaymentGatewayValidate;
use App\Rules\PaymentTypeValidate;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;

class UserWalletController extends BaseController
{
    /**
     * 我的钱包首页
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

		return $this->responseData([
            'integral' => $user->integral,
            'balance' => $user->amount()
        ]);
    }

	/**
     * 我的余额
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {
		$page = $request->input('page') ?? 1;
		$number = $request->input('number') ?? 10;

        $month = $request->input('date');

		/** @var User $user */
		$user = $request->user();

		$balance = $user->amount();

		$query = $user->balances()->latest()->latest('id');

		// 如果 筛选月份
		if ($month) {
		    [$year, $month] = explode('-', $month);
		    $query->year($year)->month($month);
        }

        $items = $query->paginate($number);

		$data = [
            'items' => UserBalanceRecordResouce::collection($items)
        ];

		if($page == 1){
            $data = array_merge($data, [
                'balance' => $balance,
                'use' => (int) $query->where('trade_type', 2)->sum('amount'),
                'income' => (int) $query->where('trade_type', 1)->sum('amount'),
            ]);
		}

		return $this->responseData($data);
    }

    /**
     * 发起充值
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function charge(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:100',
            // 'gateway' => ['required', new PaymentGatewayValidate()],
            // 'type' => [
            //     'required',
            //     new PaymentTypeValidate($request->input('gateway'))
            // ],
        ], [
            'amount.min' => '最少充值1元'
        ]);

        $recharge = DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = $request->user();

            return $recharge = $user->recharges()->create([
                'amount' => $request->input('amount'),
                'gateway' => $request->input('gateway', Config::DEFAULT_CHANNEL),
                'no' => get_order_no()
            ]);
        });

        return $this->responseData([
            'amount' => $recharge->amount,
            'order_no' => $recharge->no,
        ]);
    }
}
