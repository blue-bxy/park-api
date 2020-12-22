<?php

namespace App\Jobs;

use App\Models\Dmanger\CarAptOrder;
use App\Models\Parks\ParkServiceCallback;
use App\Packages\Payments\PaymentType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscribeResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \App\Models\Dmanger\CarApt */
    public $subscribe;
    /** @var \App\Models\User  */
    public $user;
    /** @var CarAptOrder  */
    public $aptOrder;

    /** @var \App\Models\Users\UserOrder  */
    public $order;

    /** @var mixed  */
    public $space;

    /** @var bool  */
    public $is_renewal;

    /**
     * Create a new job instance.
     *
     * @param CarAptOrder $aptOrder
     */
    public function __construct(CarAptOrder $aptOrder)
    {
        $this->aptOrder = $aptOrder;

        $this->user = $aptOrder->user;

        $this->subscribe = $aptOrder->carApt;

        $this->order = $aptOrder->order;

        // $this->park = $aptOrder;

        $this->space = $this->order->subscribeSpace;

        $this->is_renewal = $aptOrder->is_renewal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 通过停车场拿到他们的回调地址
        // 组合数据并推送
        // 给车厂推送预约数据  、楼层数、时长、预约车位编号、是否续约、续约时长、总时长
        $data = [
            'no' => $this->order->order_no, //订单号
            'mobile' => $this->user->mobile,
            'car_number' => $this->subscribe->car->car_number, //预约车位
            'carport_number' => $this->space->number, //预约车位
            // 'floor' => 1,
            'subscribe_time' => $this->aptOrder->subscribe_time, // 预约时长
            'subscribe_total_time' => (int) $this->subscribe->apt_time, // 总时长
            'is_renewal' => $this->aptOrder->is_renewal, // 是否续约
            'subscribe_start_time' => $this->subscribe->apt_start_time->timestamp,
            'subscribe_end_time' => $this->subscribe->apt_end_time->timestamp,
        ];

        $item = [
            'type' => $this->is_renewal ? PaymentType::PAYMENT_TYPE_SUBSCRIBE_RENEWAL_ORDER
                : PaymentType::PAYMENT_TYPE_SUBSCRIBE_ORDER,
            'data' => $data
        ];

        try {
            // 停车场推送地址
            $url = get_park_setting($this->subscribe->park_id, 'callback_url');

            $response = \Http::post($url, $item);

            $result = [
                'item' => $item,
                'result' => $response->json()
            ];

            logger('发送停车场预约信息', $result);

            ParkServiceCallback::query()->create([
                'park_id' => $this->subscribe->park_id ?? null,
                'url' => $url,
                'params' => $item,
                'result' => $response->json()
            ]);
        } catch (\Exception $exception) {
            //
        }
    }
}
