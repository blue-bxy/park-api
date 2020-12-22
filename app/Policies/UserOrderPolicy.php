<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use App\Models\Users\UserOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserOrderPolicy
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

    public function own(User $user, UserOrder $order)
    {
        return $user->id == $order->user_id;
    }

    public function comment(User $user, UserOrder $order)
    {
        $result = $user->id == $order->user_id && $order->canComment();

        return $result ? $result : $this->deny('不能对当前订单进行评价操作');
    }
}
