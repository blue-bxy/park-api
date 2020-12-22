<?php

namespace App\Models\Users;

use App\Models\Admin;
use App\Models\EloquentModel;
use App\Packages\JPush\JPushMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Class Message
 * @package App\Models\Users
 *
 * @property string $no
 * @property int $type
 * @property int $send_type
 * @property string $title
 * @property string $content
 * @property string|null $platform
 * @property Carbon $send_time
 * @property array $extras
 *
 * @property-read string $type_name,
 * @property-read string $send_type_name,
 * @property-read string $platform_name,
 */
class Message extends EloquentModel
{
    use SoftDeletes;

    public static $typeMaps = [
        0 => '系统',
        4 => '优惠券',
    ];

    public static $sendTypeMaps = [
        0 => '站内系统通知',
        1 => 'App通知',
        2 => '站内系统通知 + App通知',
    ];

    public static $platformMaps = [
        'all' => '所有',
        'ios' => '苹果',
        'android' => '安卓'
    ];

    protected $fillable = [
        'admin_id','no', 'send_type', 'type', 'title', 'content', 'platform', 'send_time', 'extras'
    ];

    protected $casts = [
        'extras' => 'array'
    ];

    protected $dates = [
        'send_time'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($message) {
            $message->no = get_order_no();
        });

        static::created(function ($message) {
            if (in_array($message->send_type, [1, 2])) {
                $j_msg = new JPushMessage();

                $data = $j_msg->platform($message->platform ?? 'all')
                    ->allAudience()
                    // ->cid($message->no)
                    ->alert($message->content)
                    ->toArray();

                app('jpush.push')->send($data);
            }
        });
    }

    public function getTypeNameAttribute()
    {
        return static::$typeMaps[$this->type ?? 0];
    }

    public function getSendTypeNameAttribute()
    {
        return static::$sendTypeMaps[$this->send_type ?? 0];
    }

    public function getPlatformNameAttribute()
    {
        return static::$platformMaps[$this->platform];
    }

    public function isNow()
    {
        return is_null($this->send_time);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class,'admin_id');
    }

    /**
     * 搜索查询
     * @param Builder $query
     * @param Request $request
     */
    public function scopeSearch(Builder $query,Request $request)
    {
        // 推送员
        if($name = $request->input('name')){
            $query->whereHas('admin',function ($query) use ($name){
                $query->where('name','like',"%$name%");
            });
        }

        // 推送用户类型


        // 查询的时间段
        if($time_start = $request->input('time_start')){
            $query->where('created_at','>=',$time_start);
        }

        if($time_end = $request->input('time_end')){
            $query->where('created_at','<=',$time_end);
        }
    }

    /**
     * 添加推送的消息
     * @param Request $request
     * @return mixed
     * @throws \Safe\Exceptions\JsonException
     */
    public function add(Request $request)
    {
        $data = array();

        $arr = array();

        $data['admin_id'] = ($request->user())->id;

//        $data['no'] = get_order_no();

        // 发送类型，0-站内系统，1-用户，2-都发
        if($send_type = $request->input('send_type')){
            $data['send_type'] = $send_type;
        }

        if($title = $request->input('title')){
            $data['title'] = $title;
        }

        if($content = $request->input('content')){
            $data['content'] = $content;
        }

        // 消息类型，0-系统，4-优惠券
        if($type = $request->input('type')){
            $data['type'] = $type;
        }

        if($coupon_price = $request->input('coupon_price')){
            $arr['coupon_price'] = $coupon_price;
        }

        // 优惠券的起始和结束时间
        if($coupon_start_time = $request->input('start_time')){
            $arr['coupon_start_time'] = $coupon_start_time;
        }

        if($coupon_stop_time = $request->input('stop_time')){
            $arr['coupon_stop_time'] = $coupon_stop_time;
        }

        // 优惠券的限制
        if($restrict_coupon = $request->input('restrict_coupon')){
            $arr['restrict_coupon'] = $restrict_coupon;
        }

        $data['extras'] = $arr;

        return Message::query()->create($data);
    }
}
