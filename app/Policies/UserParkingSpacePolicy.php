<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use App\Models\Users\UserParkingSpace;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserParkingSpacePolicy
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

    public function own(User $user, UserParkingSpace $space)
    {
        return $user->id == $space->user_id;
    }
}
