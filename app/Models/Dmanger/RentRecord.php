<?php

namespace App\Models\Dmanger;

use App\Models\EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;


class RentRecord extends EloquentModel
{
    use SoftDeletes;
    // 添加时白名单
    protected $fillable=[];
}
