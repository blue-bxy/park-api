<?php

namespace App\Models\Messages;

use App\Models\EloquentModel;

class MessageCode extends EloquentModel
{
    protected $fillable = [
        'action_type', 'phone', 'code', 'ip', 'sid', 'send_time', 'report_status', 'report_time', 'report'
    ];

    protected $casts = [
        'result' => 'json'
    ];
}
