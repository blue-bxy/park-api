<?php

namespace App\Models\Users;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Models\Parks\Park;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class PropertyMessage extends EloquentModel
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'title', 'content','park_type','park_property'];

    public static $park_type = [1 => '室内',2 => '室外',3 => '室内外',4 => '其他'];

    public static $park_property = [
        1 => '商业综合体',2 => '商业写字楼',3 => '商务酒店',4 => '公共场馆',5 => '医院', 6 => '产业园',
        7 => '住宅',8 => '旅游景点',9 => '物流园',10 => '建材市场',11 => '学校',12 => '交通枢纽'
    ];

    public function getParkTypeAttribute($value)
    {
        return self::$park_type[$value];
    }

    public function getParkPropertyAttribute($value)
    {
        return self::$park_property[$value];
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class,'user_id');
    }

    public function park()
    {
        return $this->belongsTo(Park::class,'park_type');
    }

    public function scopeSearch(Builder $query,Request $request)
    {

        // 推送员
        if($name = $request->input('name')){
            $query->whereHas('admin',function ($query) use ($name){
                $query->where('name','like',"%$name%");
            });
        }

        // 推送的车场类型
        if($park_type = $request->input('park_type')){
            $query->where('park_type',$park_type);
        }

    }

    public function add($request)
    {
        $user_id = ($request->user())->id;

        $title= $request->input('title');

        $content= $request->input('content');

        $park_type = $request->input('park_type');

        $park_property = $request->input('park_property');

        $ret = PropertyMessage::create([
            'user_id' => $user_id,
            'title' => $title,
            'content' => $content,
            'park_type' => $park_type,
            'park_property' => $park_property
        ]);

        return $ret;
    }
}
