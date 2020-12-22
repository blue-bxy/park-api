<?php

namespace App\Providers;

use App\Services\CustomActivityLogger;
use App\Services\CustomPaginatorService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\ActivityLogger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        // 重写 paginate toArray 方法
        $this->app->bind(LengthAwarePaginator::class, CustomPaginatorService::class);
        $this->app->bind(ActivityLogger::class, CustomActivityLogger::class);

        JsonResource::withoutWrapping();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // mysql 5.7.7以下版本
        // Schema::defaultStringLength(191);

        // 纬度
        Validator::extend('latitude', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $value);
        });

        // 经度
        Validator::extend('longitude', function ($attribute, $value, $parameters, $validator) {
            return preg_match( '/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $value);
        });
    }
}
