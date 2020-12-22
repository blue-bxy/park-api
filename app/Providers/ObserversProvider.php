<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Parks\ParkArea;
use App\Models\Parks\ParkSpace;
use App\Models\Parks\ParkStall;
use App\Observers\AdminObserver;
use App\Observers\ParkAreaObserver;
use App\Observers\ParkSpaceObserver;
use App\Observers\ParkStallObserver;
use Illuminate\Support\ServiceProvider;

class ObserversProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Admin::observe(AdminObserver::class);
        ParkStall::observe(ParkStallObserver::class);
        ParkArea::observe(ParkAreaObserver::class);
        ParkSpace::observe(ParkSpaceObserver::class);
    }
}
