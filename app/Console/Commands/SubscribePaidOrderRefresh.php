<?php

namespace App\Console\Commands;

use App\Events\Orders\Finish;
use App\Models\Dmanger\CarApt;
use App\Models\Users\UserOrder;
use Illuminate\Console\Command;

class SubscribePaidOrderRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:paid-order {--order}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '针对已预约超时未进场订单进行标记完成';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = UserOrder::query();

        if ($this->option('order')) {
            $query->where(function ($query) {
                $value = $this->option('order');
                $query->orWhere('id', $value)->orWhere('no', $value);
            });
        }

        $query->with('carApts');
        // 超时未进场
        $query->whereNotNull('paid_at')
            ->where('status', UserOrder::ORDER_STATE_PAID)
            ->whereHas('carApts', function ($query) {
                $query->where('apt_end_time', '<', now());
            })
            ->whereNull('car_in_time');

        $query->each(function (UserOrder $order) {
            // 用户未履约，不退款, 订单完成
            $order->setFinish()->save();

            /** @var CarApt $apt */
            $apt = $order->carApts;
            // 不退款，更新扣款金额
            $apt->updateTrueAmount(0)->save();

            // 预约费分账
            $apt->addDivideRecord();

            event(new Finish($order, false));
        });

        return 0;
    }
}
