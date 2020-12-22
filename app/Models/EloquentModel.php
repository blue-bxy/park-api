<?php

namespace App\Models;

use App\Services\PermissionService;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EloquentModel extends Model
{
    use LogsActivity;

    /**
     * getGuardRenameAttribute
     *
     * @return mixed|null
     */
    public function getGuardRenameAttribute()
    {
        if ($this->getOriginal('guard_name')) {
            return PermissionService::$guards[$this->guard_name] ?? null;
        }

        return null;
    }

    public function formatAmount($amount)
    {
        return number_format($amount / 100, 2);
    }

    public function getCoverUrl($fileName, $path = '')
    {
        if (is_url($fileName)) {
            return $fileName;
        }

        if (pathinfo($fileName, PATHINFO_EXTENSION)) {
            return \Storage::disk('public')->url($path . "/". $fileName);
        }

        return $fileName;
    }
}
