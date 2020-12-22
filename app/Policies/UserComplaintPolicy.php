<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use App\Models\Users\UserComplaint;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserComplaintPolicy
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

    public function own(User $user, UserComplaint $complaint)
    {
        return $user->id == $complaint->user_id;
    }
}
