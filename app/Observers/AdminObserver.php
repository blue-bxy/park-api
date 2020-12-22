<?php

namespace App\Observers;

use App\Models\Admin;

class AdminObserver
{
    public function created(Admin $admin)
    {
        $admin->update(['code' => $admin->recode]);
    }
}
