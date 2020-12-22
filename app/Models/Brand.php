<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends EloquentModel
{
    //品牌类型
    const TYPE_CAMERA = 1;      //摄像头
    const TYPE_LOCK = 2;        //地锁
    const TYPE_BLUETOOTH = 3;   //蓝牙

    use SoftDeletes;

    protected $fillable = ['name', 'type'];

    /**
     * 该品牌下的型号
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function models() {
        return $this->hasMany(BrandModel::class);
    }

}
