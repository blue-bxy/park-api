<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Properties API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::namespace('Property')->prefix('property')->name('property.')->group(function () {

    Route::post('login', 'LoginController@login')->name('login');
    Route::get('captcha/{config?}', 'LoginController@captcha')->name('captcha');

    Route::middleware('auth:property')->group(function () {
        Route::post('logout', 'LoginController@logout')->name('logout');

        Route::get('park_area', 'ParkAreaController@index')->name('park_area.index');
        Route::get('park_spaces/{park_space}/rate', 'ParkSpaceController@rate')->name('park_spaces.rate');
        Route::get('park_spaces/count', 'ParkSpaceController@count')->name('park_spaces.count');
        Route::apiResource('park_spaces', 'ParkSpaceController');
        Route::apiResource('park_rates', 'ParkRateController');
        Route::get('park_cameras', 'ParkCameraController@index')->name('park_cameras.index');
        Route::get('park_cameras/pictures', 'ParkCameraController@pictures')->name('park_cameras.pictures');
        Route::get('park_bluetooth', 'ParkBluetoothController@index')->name('park_bluetooth.index');
        Route::get('park_space_locks', 'ParkSpaceLockController@index')->name('park_space_locks.index');

        // 总记录
        Route::apiResource('park-income','ParkIncomeController')->only('index');
        Route::get('park-income-export','ParkIncomeController@export')->name('income.export');
        // 数据查询-停车记录
        Route::apiResource('car-stop','CarStopController');
        Route::get('car-stop-export','CarStopController@export')->name('stop.export');
        // 数据查询-预约记录
        Route::apiResource('car-apt','CarAptController');
        Route::get('car-apt-export','CarAptController@export')->name('apt.export');
        // 数据查询-出租记录
        Route::apiResource('car-rent','CarRentController');
        Route::get('car-rent-export','CarRentController@export')->name('rent.export');




        //提现管理-提现记录
        Route::apiResource('withdrawal-record','WithdrawalRecordsController');
        //统计前一天的车场预约单的正常结算收入和退款
        Route::get('summary-apt-order','AptOrderDayController@summary');
        //提现管理-预约余额管理
        Route::get('park_amount','PropertyBalanceController@amount')->name('property.amount');
        Route::apiResource('balance','PropertyBalanceController');
        // 提现管理-退款
        Route::get('user-refund','UserRefundController@index')->name('user_refund.index');
        //提现管理-预约余额管理-提现数据
        Route::get('withdrawal-index','PropertyBalanceController@WithdrawalIndex')->name('balance.withdrawal-index');
        //提现管理-预约余额管理-提现操作
        Route::post('balance/withdrawal','PropertyBalanceController@withdrawal');
        //预约余额明细
        Route::apiResource('apt-detail','AptOrderDetailController');
        Route::get('apt-detail-export','AptOrderDetailController@export')->name('apt-detail.export');
        Route::get('get-user-permission', 'PermissionsController@getRolePermissions')
            ->name('permission.list');

        // 首页
        Route::get('home', 'HomeController@index')->name('home');

        Route::apiResource('properties', 'PropertyController');

        // 重置密码
        Route::post('properties/{property}/reset', 'PropertyController@reset')
            ->name('properties.reset');
        Route::get('properties/{property}/permissions', 'PropertyController@permissions')
            ->name('properties.permission');
        // 同步权限
        Route::post('properties/{property}/permissions', 'PropertyController@syncPermissions')
            ->name('properties.permission.sync');
        Route::apiResource('permissions', 'PermissionsController');

        // 添加用户前获取职位角色
        Route::get('get-department-node', 'PropertyController@nodes')->name('department.nodes');

        Route::get('permission-top-node', 'PermissionsController@top')->name('permissions.top.node');
        // 部门
        Route::apiResource('departments', 'DepartmentController')->except('show');
        // 更新部门角色
        Route::post('departments/{department}/roles', 'DepartmentController@syncRoles')
            ->name('departments.roles.sync');
        // 获取部门角色列表
        Route::get('get-department-roles/{department?}', 'DepartmentController@roles')
            ->name('departments.roles');


        //优免管理
        Route::resource('discount','DiscountController');

        Route::get('car-owners', 'UserParkingSpaceController@index');
        Route::post('car-owners/{parking}', 'UserParkingSpaceController@update');

        // 首页
        Route::get('index','IndexController@index')->name('property.index');
        Route::get('index-top','IndexController@top')->name('index.top');
        Route::get('index-traffic','IndexController@traffic')->name('index.traffic');
        Route::get('index-rent','IndexController@rent')->name('index.rent');
        Route::get('index-state','IndexController@state')->name('index.state');
        Route::get('index-finance','IndexController@finance')->name('index.finance');

        Route::get('index/spaces', 'IndexController@spaces')->name('index.spaces');
        Route::get('index/devices', 'IndexController@devices')->name('index.devices');

        // 操作记录
        Route::get('activity-logs','ActivityLogController@index')->name('log.index');
    });
});

