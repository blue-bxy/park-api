<?php

namespace App\Jobs;

use App\Models\Dmanger\CarApt;
use App\Models\Parks\ParkServiceCallback;
use App\Packages\Payments\PaymentType;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscribeCancelResponse implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \App\Models\Dmanger\CarApt */
    public $subscribe;
    /** @var \App\Models\User  */
    public $user;

    /** @var \App\Models\Users\UserOrder  */
    public $order;

    /** @var mixed  */
    public $space;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CarApt $apt)
    {
        $this->user = $apt->user;

        $this->subscribe = $apt;

        $this->order = $apt->userOrder;

        $this->space = $apt->parkSpace;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'no' => $this->order->order_no, //订单号
            'mobile' => $this->user->mobile,
            'car_number' => $this->subscribe->car->car_number, //预约车位
            'carport_number' => $this->space->number, //预约车位
            // 'floor' => 1,
            'subscribe_total_time' => (int) $this->subscribe->apt_time, // 总时长
            'is_renewal' => false, // 是否续约
            'cancel_time' => time(),
            'subscribe_total' => 0,
            'subscribe_start_time' => $this->subscribe->apt_start_time->timestamp,
            'subscribe_end_time' => $this->subscribe->apt_end_time->timestamp,
        ];

        $item = [
            'type' => PaymentType::PAYMENT_TYPE_SUBSCRIBE_CANCEL,
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

            logger('发送停车场预约取消信息', $result);

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
