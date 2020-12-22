<?php


namespace App\Services;


use App\Exceptions\PaymentOrderNotFundException;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\PaymentInterface;
use App\Models\User;
use App\Packages\Payments\Config;
use App\Packages\Payments\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PaymentService
{
    public function index(PaymentRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $gateway = $request->input('gateway');

        $no = $request->input('order_no');

        $model = $user->payment()
            ->where('no', $no)
            ->whereNull('paid_at')
            ->whereNull('failed_at')
            ->firstOr(function () {
                throw new PaymentOrderNotFundException();
            });

        $params = [
            'body' => $model->body(),
            'total_amount' => $model->totalAmount(),
            'order_no' => $model->no()
        ];
        //如果是微信支付 需要提供openid
        if (str_starts_with($gateway, 'wx')) {
            $params['client_ip'] = $request->ip();
            // $params['openid'] = $user->openid();
        }

        //$params['currency'] =  'CNY';
        $params['gateway'] = $gateway;

        $params['attach'] = json_encode($model->getAttach($request));

        $result = \App\Packages\Payments\Payment::charge($gateway, $params);

        // dd($result);

        return $result;
    }

    public function notify(array $attributes)
    {
        $order_no = $attributes['order_no'];

        /** @var Payment $payment */
        $payment = Payment::query()
            ->with('user')
            ->where('no', $order_no)
            ->whereNull('paid_at')
            ->whereNull('failed_at')
            ->lockForUpdate()
            ->firstOr(function () {
                throw new PaymentOrderNotFundException();
            });

        $attributes['total_amount'] = $attributes['total_amount'] ?? $payment->amount();

        return $payment->paid($attributes);
    }

    public function failed(Request $request, $no = null)
    {
        //
    }

    public function refund(PaymentInterface $model, string $refund_amount, string $refund_no, string $type)
    {
        \DB::transaction(function () use ($model, $refund_amount, $refund_no, $type) {
            // 收入到钱包
            $model->user->income($model, $refund_amount, $type);
            if (!in_array($model->gateway(), [Config::DEFAULT_CHANNEL])) {
                // 发起原路退款
                $this->refundAmountByGateway($model, $refund_amount, $refund_no);

                $model->user->expenditure($model, $refund_amount, PaymentType::PAYMENT_TYPE_AUTO_REFUND);
            }
        });
    }

    public function refundAmountByGateway(PaymentInterface $model, $refund_amount, $refund_no)
    {
        $gateway = $model->gateway();

        $options = [
            'order_no'      => $model->orderNo(),
            'refund_no'     => $refund_no,
            'total_amount'  => $model->amount(),
            'refund_amount' => $refund_amount,
        ];

        return app('payment')->refund($gateway, $options);
    }
}
