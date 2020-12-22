<?php

namespace App\Console;

use App\Services\SummaryOrderService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        TraitMakeCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // 关闭预约单失效订单
        $schedule->command('subscribe:order', ['--cancel'])->everyMinute();
        // 针对已预约超时未进场标记已完成
        $schedule->command('subscribe:paid-order')->everyMinute();
        // 刷新过期订单状态
        $schedule->command('coupon:user')->everyMinute();
        // 费率整点切换
        $schedule->command('rate:refresh')->hourly();

        $schedule->call(function () {
            app(SummaryOrderService::class)->summary();
        })->daily()->at('1:00');

        $schedule->call(function () {
            // 统计停车场数据
            $server = app(SummaryOrderService::class);

            $server->day(); // 停车场 汇总
            $server->owner(); // 业主
        })->daily()->after(function () {
            // 统计平台数据
            $server = app(SummaryOrderService::class);

            $server->platform(); //平台
        });

        $schedule->call(function () {
            app(SummaryOrderService::class)->month();
        })->monthly();

        //设备信息刷新
        $schedule->command('device:refresh')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
