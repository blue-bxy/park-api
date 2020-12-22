<?php

namespace App\Providers;

use App\Models\Users\UserCar;
use App\Models\Users\UserComplaint;
use App\Models\Users\UserOrder;
use App\Models\Users\UserParkingSpace;
use App\Policies\UserCarPolicy;
use App\Policies\UserComplaintPolicy;
use App\Policies\UserOrderPolicy;
use App\Policies\UserParkingSpacePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        UserCar::class => UserCarPolicy::class,
        UserComplaint::class => UserComplaintPolicy::class,
        UserOrder::class => UserOrderPolicy::class,
        UserParkingSpace::class => UserParkingSpacePolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
