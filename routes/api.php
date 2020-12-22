<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('App')->prefix('api')->group(function () {
    // 多渠道登陆前获取授权信息
    Route::get('auth/{service}', 'LoginController@socialite');
    //多渠道第三方登陆回调
    Route::get('auth/{service}/callback', 'LoginController@handleCallback');

    // 多渠道登陆，暂支持手机号+（验证码或密码）
    Route::post('auth/{service}/login', 'LoginController@login');
    Route::get('/login/send-code', 'LoginController@sendCode')
        ->middleware('throttle:10,1');  // 一分钟一次
    // 获取版本号
    Route::get('get-version', "VersionController@version");

    // 第三方支付回调地址
    Route::post('payment/{gateway}/notify', 'PaymentController@notify');

    Route::get('parks', 'SubscribeController@index');
    Route::get('parks/{park}', 'SubscribeController@show');
    Route::get('parks/{park}/comment', 'ParkController@comments');

    Route::get('user-guide','SettingController@userGuide');
    Route::get('about','SettingController@about');
    // 通过关键词模糊查询停车场
    Route::get('get-parks-by-keyword', 'UserParkingSpaceController@park');
    // 可出租的停车场列表
    Route::get('support-parks', 'ParkController@index');
    // 周边停车场
    Route::get('get-around-parks', 'ParkController@around')->middleware('user.login_log');
    Route::get('get-maps-by-keyword', 'SubscribeController@search');

    // 根据车位选择 获取出租价格表
    Route::get('get-rental-by-space/{space}', 'SubscribeController@getSpaceRate');

    // 获取反寻状态
    Route::get('get-find-car-status', 'HomeController@index');

    Route::get('get-systems', 'HomeController@info');
    // 扫码领券
    Route::get('coupon-scan', 'UserCouponController@scan');

    Route::middleware(['auth:api', 'notice'])->group(function () {
		Route::post('password/reset','LoginController@resetPassword');
		Route::post('logout','LoginController@logout');

		// 绑定手机号
		Route::post('bind-mobile', 'MobileController@bind')->name('bind.mobile');
		// 个人中心
		Route::get('user', 'UserController@index');
        // 个人资料
		Route::get('profile', 'UserController@profile');
		//更新个人资料
        Route::post('profile', 'UserController@update');
        // 下单付款前先获取支付方式列表
        Route::get('get-pay-method-list', 'PaymentController@gateway');
        // 付款结算
        Route::post('payment', 'PaymentController@index');
        //查询支付状态
        Route::get('payment-query', 'PaymentController@query');
        // 发起充值请求
        Route::post('wallet-charge', 'UserWalletController@charge');

        Route::apiResource('cars', 'UserCarController')->except('show');
        // 设置默认、取消默认
        Route::post('cars/{car}/set-default', 'UserCarController@default');
        // 车主认证
        Route::post('car-owner-verify/{car?}', 'UserCarVerifyController@verify');
        Route::post('car-owner-verify-upload/{car?}', 'UserCarVerifyController@upload');

		// 我的钱包首页
        Route::get('wallet', 'UserWalletController@index');
		// 我的余额
		Route::get('balance','UserWalletController@balance');

		Route::get('coupons', 'UserCouponController@index');
		Route::post('coupons', 'UserCouponController@store');

		// 历史搜索记录
        Route::get('history-search', 'SearchController@index');
        Route::delete('history-search/{search}', 'SearchController@destroy');

		// 投诉
		Route::apiResource('complaints', 'UserComplaintController')
            ->except('show', 'update');
		// 订单行程
		// Route::apiResource('orders', 'UserOrderController');
        Route::get('orders', 'UserOrderController@index');
        Route::get('orders/{order}', 'UserOrderController@show');
        Route::post('orders/{order}/cancel', 'UserOrderController@cancel');
        // 测试阶段模拟汽车入场
        Route::get('orders/{order}/entrance', 'UserOrderController@entrance');
        Route::get('orders/{order}/stop', 'UserOrderController@stop');
        Route::get('orders/{order}/out', 'UserOrderController@out');
        // 重新分配车位
        Route::get('reallocate', 'UserOrderController@reallocate');
        // 寻车
        Route::get('find-car', 'UserOrderController@find');
        // 结束寻车
        Route::get('exit-find-car/{order}', 'UserOrderController@exit');
		// 添加评论
		Route::post('orders/{order}/comment', 'UserCommentController@store');

		// 设备操作
        Route::post('orders/{order}/unlock', 'UserOrderController@unlock');

        // 我的车位
        Route::get('parking-space', 'UserParkingSpaceController@index');
        // 提交车位认证
        Route::post('parking-space', 'UserParkingSpaceController@store');

        // 出租记录
        Route::get('rentals', 'RentalsController@index');

        Route::post('parking-space-rental', 'RentalsController@store');
        Route::post('parking-space-open-rental/{parking_id}', 'RentalsController@update');
        // 申请开通小区
        Route::post('parking-lot-open-apply', 'SubscribeController@apply')
            ->middleware('throttle:10,1'); // 限流：1分钟一次请求
        // 收益明细
        Route::get('rental-income-record', 'RentalsController@incomeRecord');
        // 申请提现
        Route::post('rental-withdrawal', 'RentalsController@withdrawal');
        // 提交预约
        Route::post('subscribe', 'SubscribeController@subscribe');
        // 预约延长
        Route::post('subscribe/renewal', 'SubscribeController@renewal');
        // 定时获取延时订单
        Route::get('get-not-entered-notice', 'SubscribeController@notEntered');
        // 取消续费提醒
        Route::post('cancel-renewal-notice', 'SubscribeController@cancelRenewalNotice');

        // 第三方账号绑定情况
        Route::get('account-binds', 'UserController@account');

        //多渠道第三方登陆回调
        Route::get('auth/{service}/bind', 'UserController@bind');

		Route::apiResources([
			// 'users' => 'UserController',
			// 'cars' => 'UserCarController',
			// 'orders' => 'UserOrderController',
			// 'comments' => 'UserCommentController',
			// 'coupons' => 'UserCouponController',
			'collects' => 'UserCollectController',
			// 'complaints' => 'UserComplaintController',
			'messages' => 'UserMessageController',
		]);

		Route::get('notice/get-coupon-list', 'UserMessageController@coupon');
		Route::get('notice/get-system-list', 'UserMessageController@system');
		Route::get('notice/get-order-list', 'UserMessageController@order');


        // 添加收藏
		Route::get('parks/{park}/favorite', 'ParkController@favorite');

		Route::get('parks/{park}/remove-favorite', 'ParkController@deleteFavorite');
    });

    // 第三方 设备 回调
    Route::any('device/{device}/callback', 'DeviceCallbackController');
    // 车场服务商数据回调
    Route::any('park-service/{code}/callback', 'ParkCallbackController');

    // 短信回调
    Route::any('sms-report/{gateway}/callback', 'MessageReportController@report');

    // 极光设备初始化
    Route::post('register-jpush-device', 'DeviceController@jpush');
});
