<?php

namespace App\Http\Controllers\App;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\App\PaymentGatewayResource;
use App\Models\PaymentGateway;
use App\Models\Recharge;
use App\Models\User;
use App\Packages\Payments\Config;
use App\Packages\Payments\Payment;
use App\Services\PaymentService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaymentController extends BaseController
{
    public function index(PaymentRequest $request, PaymentService $paymentService)
    {
        $gateway = $request->input('gateway');

        $res = $gateway == Config::DEFAULT_CHANNEL
            ? $paymentService->notify($request->all()) : $paymentService->index($request);

        if ($res instanceof Model && $res->has_paid) {
            return $this->responseSuccess();
        }

        return $this->responseData($res);
    }

    public function notify(Request $request, $gateway, PaymentService $paymentService)
    {
        $options = file_get_contents("php://input");

        try {
            $responseData = Payment::notify($gateway, $options);
            //第三方回调
            $paymentService->notify($responseData);

            if (str_starts_with($gateway, 'wx') || $gateway == Config::WX_CHANNEL_COMMON) {
                $result = [
                    'return_code' => 'SUCCESS',
                    'return_msg'  => 'OK'
                ];
                return response(toXml($result));
            } else {
                die('success'); // aliPay
            }
        } catch (\Exception $exception) {
            return $this->responseFailed('order notify validate error ['. $exception->getMessage(). ']');
        }
    }

    /**
     * 获取付款方式
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function gateway(Request $request)
    {
        $this->validate($request, [
            'order_no' => 'required',
            'platform' => 'sometimes|required'
        ]);

        /** @var User $user */
        $user = $request->user();

        $order_no = $request->input('order_no');

        $platform = $request->input('platform');

        $order = $user->payment()->where('no', $order_no)->firstOrFail();

        if ($order->has_paid) {
            return $this->responseFailed('该笔订单已付款，无需重复支付');
        }

        $gateways = PaymentGateway::query()
            ->where('enabled', true)
            // ->whereJsonContains('platform', $platform)
            ->orderBy('sort')
            ->get();

        //如果是充值订单 排除余额支付
        $gateways = $gateways->filter(function ($model) use ($order) {
            // 充值只能使用第三方支付
            if ($order && $order->payable instanceof Recharge) {
                return !in_array($model->gateway, [Config::DEFAULT_CHANNEL]);
            }

            // 普通付款
            return true;
        });

        $data = [
            'order_no' => $order->no,
            'extra' => $order->context,
            'isPaid'  => $order->has_paid,
            'payMethodList' => PaymentGatewayResource::collection($gateways),
            'userInfo' => [
                'balance'      => $user->balance
            ]
        ];

        return $this->responseData($data);
    }

    /**
     * query
     *
     * @param Request $request
     * @param PaymentService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function query(Request $request, PaymentService $service)
    {
        $request->validate([
            'order_no' => 'required',
            //'gateway' => 'required'
        ]);

        $order_no = $request->input('order_no');
        /** @var User $user */
        $user = $request->user();

        $order = $user->payment()
            ->where('no', $order_no)
            ->first();

        $has_paid = false;

        $data = [
            'has_paid' => $has_paid,
        ];

        if ($order) {
            $gateway = $order->gateway;

            if ($gateway == Config::DEFAULT_CHANNEL) {
                $has_paid = $order->has_paid;
            } else {
                $result = Payment::query($gateway, $order_no);
                $has_paid = $result['has_paid'];
                // 如果订单状态为未支付，而第三方订单状态已成功，将更新订单状态
                if (!$order->has_paid && $has_paid) {
                    $attributes = Payment::instanceNotify($gateway, $result['result']);
                    $order = $service->notify($attributes);
                }

            }

            $data['has_paid'] = $has_paid;
            $data['paid_time'] = $order->paid_at->timestamp ?? null;
        }

        return $this->responseData($data);
    }
}
