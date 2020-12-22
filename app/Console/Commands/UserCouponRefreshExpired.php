<?php

namespace App\Console\Commands;

use App\Models\Users\UserCoupon;
use Illuminate\Console\Command;

class UserCouponRefreshExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷新过期优惠券';

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
        return UserCoupon::query()
            ->whereNull('use_time')
            ->where('expiration_time', '<', now())
            ->where('status', UserCoupon::STATUS_PENDING)
            ->update([
            'status' => UserCoupon::STATUS_EXPIRED
        ]);
    }
}
