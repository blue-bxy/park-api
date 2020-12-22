<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Users\UserCar;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserCarPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function own(User $user, UserCar $car)
    {
        return $user->id == $car->user_id;
	}
}
