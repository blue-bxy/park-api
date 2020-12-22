<?php


namespace App\Services;


use Spatie\Activitylog\ActivityLogger;

class CustomActivityLogger extends ActivityLogger
{
    public function log(string $description)
    {
        if ($this->logStatus->disabled()) {
            return;
        }

        $activity = $this->activity;

        $activity->description = $this->replacePlaceholders(
            $activity->description ?? $description,
            $activity
        );

        $activity->last_ip = request()->ip();

        $activity->save();

        $this->activity = null;

        return $activity;
    }
}
