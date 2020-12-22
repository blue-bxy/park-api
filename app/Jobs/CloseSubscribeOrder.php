<?php

namespace App\Jobs;

use App\Models\Dmanger\CarAptOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CloseSubscribeOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $user_order;

    /**
     * CloseSubscribeOrder constructor.
     * @param CarAptOrder $order
     */
    public function __construct(CarAptOrder $order)
    {
        $this->order = $order;

        $this->user_order = $order->order;


        // 设置延迟执行时间
        $this->delay($order->expired_at ?? now()->addMinutes(15));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user_order->hasPaid()) {
            return;
        }

        $this->user_order->cancel(false);
    }
}
