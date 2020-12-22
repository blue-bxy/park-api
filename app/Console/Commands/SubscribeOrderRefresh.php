<?php

namespace App\Console\Commands;

use App\Models\Dmanger\CarAptOrder;
use App\Models\Users\UserOrder;
use Illuminate\Console\Command;

class SubscribeOrderRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:order {--order} {--cancel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '预约订单处理';

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
     * @return mixed
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

        // 失效处理
        if ($this->hasOption('cancel')) {
            $query->whereHas('carApts.aptOrder', function ($query) {
                $query->where('expired_at', '<', now());

                $query->where('status', CarAptOrder::STATUS_PENDING);
                $query->whereNull('paid_at');
                return $query;
            });

            $query->where('status', UserOrder::ORDER_STATE_PENDING);
            $query->each(function ($order) {
                $order->cancel(false);
            });
        }

        // $this->info('subscribe order command');
    }
}
