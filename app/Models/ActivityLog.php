<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    public static $eventMaps = [
        'created' => '添加',
        'updated' => '更新',
        'deleted' => '删除',
        'restored' => '搜索',
    ];

    public static $logNames = [
        'admin' => '云端用户',
        'carApt' => '预约',
        'carStop' => '停车记录',
        'userOrder' => '总数据',
        'carRent' => '出租',
        'userPaymentLog' => '用户流水记录',
        'excelExport' => '报表导出',
        'property' => '物业端用户',
        'reminder' => '催收管理',
        'reminder_record' => '催收管理记录'
    ];

    public function getDescAttribute()
    {
        $desc = $this->description;

        if (in_array($this->description, ['created', 'updated', 'deleted', 'restored'])) {
            $action = static::$eventMaps[$this->description];

            if (array_key_exists($this->log_name, static::$logNames)) {
                $desc = "[$action]-[". static::$logNames[$this->log_name]."]";
            }
        }

        return $desc;
    }
}
