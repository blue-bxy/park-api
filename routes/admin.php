<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {

    Route::post('login', 'LoginController@login')->name('login');
    Route::get('captcha/{config?}', 'LoginController@captcha')->name('captcha');

    Route::middleware(['auth:admin'/*, 'permission:admin'*/])->group(function () {
        Route::post('logout', 'LoginController@logout')->name('logout');

        Route::get('get-user-permission', 'PermissionsController@getRolePermissions')
            ->name('admins.permission.list');

        // 首页
        Route::get('home', 'HomeController@index')->name('home');
        // 首页地图
        Route::get('home_map', 'HomeController@map')->name('home.map');

        Route::apiResource('roles', 'RolesController')->except('show');
        Route::get('roles/{role}/permissions', 'RolesController@permissions')->name('roles.permissions');
        Route::post('roles/{role}/permissions', 'RolesController@syncPermissions')
            ->name('roles.permissions.sync');
        Route::apiResource('permissions', 'PermissionsController');
        // 云平台用户
        Route::apiResource('admins', 'AdminController')->except('show');
        // 用户端
        Route::get('users', 'UsersController@index')->name('users.index');
        Route::delete('users/{user}', 'UsersController@destroy')->name('users.destroy');
        // 解绑用户车牌
        Route::post('users/{user}/unbind', 'UsersController@unbind')->name('users.unbind');
        // 重置密码
        Route::post('admins/{admin}/reset', 'PasswordResetController@reset')->name('password.reset');
        Route::get('admins/{admin}/permissions', 'AdminController@permissions')->name('admins.permission');
        // 同步权限
        Route::post('admins/{admin}/permissions', 'AdminController@syncPermissions')
            ->name('admins.permission.sync');
        // 添加用户前获取职位角色
        Route::get('get-department-node', 'AdminController@nodes')->name('department.nodes');

        Route::get('permission-top-node', 'PermissionsController@top')->name('permissions.top.node');
        // 部门
        Route::apiResource('departments', 'DepartmentController')->except('show');
        // 更新部门角色
        Route::post('departments/{department}/roles', 'DepartmentController@syncRoles')
            ->name('departments.roles.sync');
        // 获取部门角色列表
        Route::get('get-department-roles/{department?}', 'DepartmentController@roles')
            ->name('departments.roles');
        // 职位
        // Route::apiResource('positions', 'PositionsController')->except('show');
        // 用户操作日志
        Route::get('activity-logs', 'ActivityLogController@index')->name('activity.logs');
        Route::get('activity/create', 'ActivityLogController@create')->name('activity.excel');

        //车场列表（简化版）
        Route::get('parks/simplified_list', 'ParkController@simplifiedList')->name('parks.simplified_list');
        //车场设置侧边栏菜单
        Route::get('parks/sidebar', 'ParkController@sidebar')->name('parks.sidebar');
        Route::put('parks/{park}/property', 'ParkController@setProperty')->name('parks.set_property');
        //停车场路由
        Route::resource('/parks','ParkController');
        //省
        Route::get('/provinces','RegionController@provinces')->name('province');
        //市
        Route::get('/cities','RegionController@cities')->name('city');
        //区
        Route::get('/countries','RegionController@countries')->name('country');

        //通讯参数设置
        Route::get('park_gates/export', 'ParkGateController@export')->name('park_gates.export');
        Route::apiResource('park_gates', 'ParkGateController');

        //停车场区域
        Route::get('park_area/{park_area}/brand', 'ParkAreaController@getBrand')->name('park_area.get_brand');
        Route::put('park_area/{park_area}/brand', 'ParkAreaController@setBrand')->name('park_area.set_brand');
        Route::apiResource('park_area', 'ParkAreaController');

        Route::post('park_area/{area}/default', 'ParkAreaController@default')->name('park_area.default');

        Route::apiResource('brands', 'BrandController')->only(['index']);
        Route::get('brand_models', 'BrandController@listWIthModels');

        Route::get('park_virtual_spaces', 'ParkVirtualSpaceController@index')->name('park_virtual_spaces.index');
        Route::post('park_virtual_spaces/init', 'ParkVirtualSpaceController@init')->name('park_virtual_spaces.init');

        //车位
        Route::post('park_spaces/multi', 'ParkSpaceController@multiStore');
        Route::get('park_spaces/import_template', 'ParkSpaceController@importTemplate');
        Route::post('park_spaces/import', 'ParkSpaceController@import');
        Route::get('park_spaces/export', 'ParkSpaceController@export');
        Route::apiResource('park_spaces', 'ParkSpaceController');

        //费率
        Route::apiResource('park_rates', 'ParkRateController');

        //摄像头分组
        Route::prefix('park_camera_groups')->name('park_camera_groups.')->group(function () {
            Route::post('{park_camera_group}/cameras', 'ParkCameraGroupController@storeCamera')->name('store_camera');
            Route::delete('{park_camera_group}/cameras', 'ParkCameraGroupController@deleteCamera')->name('delete_camera');
            Route::get('cameras_without_group', 'ParkCameraGroupController@camerasWithoutGroup')->name('cameras_without_group');
        });
        Route::apiResource('park_camera_groups', 'ParkCameraGroupController');

        //停车场摄像头
        Route::prefix('park_cameras')->name('park_cameras.')->group(function () {
            Route::get('{park_camera}/spaces', 'ParkCameraController@spaces')->name('spaces');
            Route::post('{park_camera}/spaces', 'ParkCameraController@storeSpace')->name('store_space');
            Route::delete('{park_camera}/spaces', 'ParkCameraController@deleteSpaces')->name('delete_spaces');
            Route::get('import_template', 'ParkCameraController@importTemplate');
            Route::post('import', 'ParkCameraController@import')->name('import');
            Route::get('export', 'ParkCameraController@export')->name('export');
        });
        Route::apiResource('park_cameras', 'ParkCameraController');

        //停车场蓝牙
        Route::prefix('park_bluetooth')->name('park_bluetooth.')->group(function () {
            Route::get('{park_bluetooth}/spaces', 'ParkBluetoothController@spaces')->name('spaces');
            Route::post('{park_bluetooth}/spaces', 'ParkBluetoothController@storeSpace')->name('spaces.store');
            Route::delete('{park_bluetooth}/spaces', 'ParkBluetoothController@deleteSpaces')->name('spaces.destroy');
            Route::get('import_template', 'ParkBluetoothController@importTemplate');
            Route::post('import', 'ParkBluetoothController@import')->name('import');
            Route::get('export', 'ParkBluetoothController@export')->name('export');
        });
        Route::apiResource('park_bluetooth', 'ParkBluetoothController');

        //停车场地锁
        Route::prefix('park_space_locks')->name('park_space_locks.')->group(function () {
            Route::get('{park_space_locks}/spaces', 'ParkSpaceLockController@spaces')->name('spaces');
            Route::post('{park_space_locks}/spaces', 'ParkSpaceLockController@spaces')->name('spaces.store');
            Route::delete('{park_space_locks}/spaces', 'ParkSpaceLockController@spaces')->name('spaces.destroy');
            Route::get('import_template', 'ParkSpaceLockController@importTemplate');
            Route::post('import', 'ParkSpaceLockController@import')->name('import');
            Route::get('export', 'ParkSpaceLockController@export')->name('export');
        });
        Route::apiResource('park_space_locks', 'ParkSpaceLockController');

        // 申请开通小区
        Route::get('parking-lot-apply', 'ParkingLotOpenApplyController@index')
            ->name('parking-lot-apply.index');
        // 修改
        Route::post('parking-lot-apply/{parking_lot_apply}', 'ParkingLotOpenApplyController@update')
            ->name('parking-lot-apply.update');
        // 受理、废弃
        Route::post('parking-lot-apply/{parking_lot_apply}/process', 'ParkingLotOpenApplyController@process')
            ->name('parking-lot-apply.process');
        // 导出
        Route::get('parking-lot-apply-export', 'ParkingLotOpenApplyController@export')
            ->name('parking-lot-apply.export');

        //财务管理-订单管理
        Route::Resource('orders-refund','OrdersRefundController');
        //财务管理-提现管理
        Route::Resource('withdrawal','WithdrawalsController');
        //财务管理-结算管理-订单管理
        Route::Resource('settle-order','SettleOrderController');
        //财务管理-结算管理-退款管理
        Route::Resource('settle-refund','SettleRefundController');
        //财务管理-订单管理-充值
        Route::Resource('recharge','RechargeController');
        //财务管理-订单管理-预约停车
        Route::Resource('apt-order','AptOrderController');
        //财务管理-订单管理-出租车位
        Route::Resource('rental','RentalOrderController');
        //财务管理-结算管理-提现调整
        Route::Resource('adjust','AdjustWithdrawalController');
        //财务管理-结算管理-调整记录
        Route::Resource('record','RecordController');
        //财务管理-结算管理-提现操作
        Route::Resource('operation','OperationController');
        //财务管理-结算管理-付款申请
        Route::Resource('apply','ApplyController');
        //财务管理-结算管理-付款
        Route::post('apply/payment','ApplyController@payment');
        // 财务管理-账号管理
        Route::Resource('account-park','AccountManageController');
        //财务管理-结算管理-坏账收款
        Route::Resource('bad-credit','BadCreditController');
        //财务管理-结算管理-记账明细
        Route::Resource('detail-account','DetailAccountController');
        //财务管理-结算管理-对账管理-车场账单
        Route::Resource('park-bill','ParkBillController');
        //财务管理-结算管理-对账管理-平台汇总
        Route::Resource('platform','PlatformRecordController');

        //用户优免记录
        Route::put('user_coupons/{user_coupon}/invalid', 'UserCouponController@invalid')->name('user_coupons.invalid');
        Route::get('user_coupons/export', 'UserCouponController@export')->name('user_coupons.export');
        Route::apiResource('user_coupons', 'UserCouponController')->only(['index', 'show']);
        //财务管理-优免管理-优免记录
        Route::put('discount/{discount}/invalid', 'DiscountController@invalid')->name('discount.invalid');
        Route::get('discount/export', 'DiscountController@export')->name('discount.export');
        Route::get('discount/rules','DiscountController@rules')->name('discount.rules');
        Route::apiResource('discount','DiscountController')->only(['index', 'store', 'show']);
        //财务管理-优免管理-优免用户规则
        Route::get('discount-user/export', 'DiscountUserRuleController@export')->name('discount-user.export');
        Route::apiResource('discount-user','DiscountUserRuleController');
        //财务管理-优免管理-优免规则
        Route::get('discount-rule/export', 'DiscountRuleController@export')->name('discount-rule.export');
        Route::apiResource('discount-rule','DiscountRuleController');
        //财务管理-优免管理-优免车场
        Route::get('discount-park/export', 'DiscountParkRuleController@export')->name('discount-park.export');
        Route::Resource('discount-park','DiscountParkRuleController');
        //财务管理-平台收入-车场收益
        Route::Resource('park-earnings','ParkEarningsController');
        // 车场收益中的平台收益
        Route::get('platform-earnings','PlatformRecordController@earnings')->name('platform-earnings');
        //财务管理-平台收入-用户收益
        Route::Resource('user-earnings','UserEarningsController');
        //财务管理-平台收入-停车手续费
        Route::Resource('parking-fee','ParkingFeeController');
        //财务管理-平台收入-预约手续费
        Route::Resource('booking-fee','BookingFeeController');
        Route::post('change-fee/{id}','BookingFeeController@change')->name('book.change');
        // 后付费订单管理
        // 待收金额统计
        Route::get('reminder-amount','ReminderController@reminderAmount')->name('reminder.amount');
        // 自动催收设置
        Route::get('get-reminder-setting','ReminderController@getReminderSet')->name('reminder.getSetting');
        Route::post('reminder-set','ReminderController@reminderSet')->name('reminder.set');
        // 催收管理
        Route::get('reminder','ReminderController@index')->name('reminder.index');
        Route::get('reminder-export','ReminderController@export')->name('reminder.export');
        // 添加催收记录
        Route::post('add-reminder-records/{state}','ReminderController@addRecords')->name('reminder.record');
        // 催收记录
        Route::get('reminder-records','ReminderRecordController@index')->name('reminder.records');
        Route::get('reminder-records-export','ReminderRecordController@export')->name('reminder.recordsExport');
        /**
         * 数据管理
         */
        // 车场收入路由
        Route::resource('parkincome','ParkIncomeController');
        // 预约路由
        Route::resource('carapt','CarAptController');
        // 预约的数据修改操作
        Route::post('carapt/editData','CarAptController@editData')->name('carapt.editData');
        // 停车记录路由
        Route::get('carstop','CarStopController@index');
        Route::get('carstop-show','CarStopController@showImage')->name('carstop_show');
        Route::get('carstop/create','CarStopController@create');
        // 车位出租
        Route::apiResource('carrent','CarRentController')->only('index', 'update');
        // 出租车位的数据导出
        Route::get('carrent-export','CarRentController@export')->name('carrent.export');
        // 报表导出路由
        Route::apiResource('excels','ExcelController')->only('index', 'destroy');
        // 下载报表的ajax路由
        Route::get('excels/{excel}/download','ExcelController@download')->name('excels.download');
        // 操作记录路由
        Route::resource('actionrecord','ActionRecordController');
        // 用户流水记录
        Route::resource('user-balance','UserBalanceController');

        // 物业用户
        Route::apiResource('properties', 'PropertiesController')->except('show');
        Route::get('properties/{property}/permissions', 'PropertiesController@permissions')
            ->name('properties.permission');
        Route::get('properties/{property}/park', 'PropertiesController@getPark')->name('properties.park');
        // 同步权限
        Route::post('properties/{property}/permissions', 'PropertiesController@syncPermissions')
            ->name('properties.permission.sync');
        // 重置物业用户密码
        Route::post('properties/{properties}/resetPropertiesPwd', 'PasswordResetController@resetPropertiesPwd')
            ->name('password.resetPropertiesPwd');
        Route::post('properties/{property}/reset', 'PasswordResetController@resetPropertiesPwd')
            ->name('properties.password.reset');

        // 用户流水记录
        Route::resource('userpayment','UserPaymentLogController');

        // 车位认证
        Route::get('user-parking-space', 'UserParkingSpaceController@index');
        Route::put('user-parking-space/{parking_id}', 'UserParkingSpaceController@update');

        //用户评价
        Route::resource('comment','UserCommentController');
        //用户投诉
        Route::resource('complaint','UserComplaintController');

        // 用户消息推送列表
        Route::get('msg','MessageController@index')->name('msg.index');
        // 用户添加消息模块
        Route::post('create-msg','MessageController@create')->name('msg.create');
        // 物业消息推送列表
        Route::get('property-msg','PropertyMessageController@index')->name('property_msg.index');
        // 物业添加消息模块
        Route::post('create-property-msg','PropertyMessageController@create')->name('property_msg.create');
        // 用户通知和关于我们
        Route::get('setting','SettingController@index')->name('setting.index');
        Route::post('setting-save','SettingController@save')->name('setting.save');
        // 配置信息的，版本控制
        Route::resource('version','VersionController');
    });
});

