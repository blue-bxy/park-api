<?php

namespace App\Providers;

use App\Events\Login;
use App\Events\Logout;
use App\Events\SubscribeCarport;
use App\Listeners\LoginActivityLogger;
use App\Listeners\LogoutActivityLogger;
use App\Listeners\SubscribeNotice;
use App\Listeners\SubscribeRental;
use App\Listeners\SubscribeSend;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        Login::class => [
            LoginActivityLogger::class
        ],

        Logout::class => [
            LogoutActivityLogger::class
        ],

        // 预约车位
        SubscribeCarport::class => [
            // 给停车场发送预约车辆数据
            SubscribeSend::class,
            // 给业主增加出租记录
            SubscribeRental::class,
            // 增加行程消息
            SubscribeNotice::class
        ],

        // 取消预约
        'App\Events\SubscribeCarportReverse' => [
            // 给停车场发送取消预约车辆数据
            'App\Listeners\SubscribeReverse',
            // 出租记录更新状态为取消
            'App\Listeners\SubscribeRentalReverse',
        ],

        'App\Events\Orders\Finish' => [
            // 释放车位
            'App\Listeners\Orders\ReleaseSpace',
            // 出租记录更新状态为完成
            'App\Listeners\Orders\SubscribeRentalFinish',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
