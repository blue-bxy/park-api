<?php

namespace App\Models\Parks;

use App\Models\Customers\ProjectGroup;
use App\Models\Dmanger\CarRent;
use App\Models\EloquentModel;
use App\Models\Financial\AccountManage;
use App\Models\Property;
use App\Models\Regions\City;
use App\Models\Regions\Country;
use App\Models\Regions\Province;
use App\Models\Traits\HasParkRate;
use App\Models\Traits\HasParkSetting;
use App\Models\Coupons\Coupon;
use App\Models\Users\UserComment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

/**
 * Class Park
 * @package App\Models\Parks
 *
 * @property string $park_number
 */
class Park extends EloquentModel
{
    const STATE_OFF = 0;    //停用
    const STATE_ON = 1;     //启用
    const STATES = [
        self::STATE_OFF => '停用',
        self::STATE_ON => '启用'
    ];

    const PROPERTY_COMMERCIAL_COMPLEX = 1;          //商业综合体
    const PROPERTY_COMMERCIAL_OFFICE = 2;           //商业写字楼
    const PROPERTY_BUSINESS_HOTEL = 3;              //商务酒店
    const PROPERTY_PUBLIC = 4;                      //公共场馆
    const PROPERTY_HOSPITAL = 5;                    //医院
    const PROPERTY_INDUSTRIAL_PARK = 6;             //产业园
    const PROPERTY_RESIDENCE = 7;                   //住宅
    const PROPERTY_TOURIST_ATTRACTION = 8;          //旅游景点
    const PROPERTY_LOGISTICS_PARK = 9;              //物流园
    const PROPERTY_BUILDING_MATERIALS_MARKET = 10;  //建材市场
    const PROPERTY_SCHOOL = 11;                     //学校
    const PROPERTY_TRANSPORTATION_JUNCTION = 12;    //交通枢纽
    const PROPERTIES = [
        self::PROPERTY_COMMERCIAL_COMPLEX => '商业综合体',
        self::PROPERTY_COMMERCIAL_OFFICE => '商业写字楼',
        self::PROPERTY_BUSINESS_HOTEL => '商务酒店',
        self::PROPERTY_PUBLIC => '公共场馆',
        self::PROPERTY_HOSPITAL => '医院',
        self::PROPERTY_INDUSTRIAL_PARK => '产业园',
        self::PROPERTY_RESIDENCE => '住宅',
        self::PROPERTY_TOURIST_ATTRACTION => '旅游景点',
        self::PROPERTY_LOGISTICS_PARK => '物流园',
        self::PROPERTY_BUILDING_MATERIALS_MARKET => '建材市场',
        self::PROPERTY_SCHOOL => '学校',
        self::PROPERTY_TRANSPORTATION_JUNCTION => '交通枢纽'
    ];

    const OPERATION_STATE_MANUFACTURING = 1;            //运营
    const OPERATION_STATE_CONSTRUCTING = 2;             //在建
    const OPERATION_STATE_ABNORMALLY_MANUFACTURING = 3; //异常运营
    const OPERATION_STATE_ACCOUNT_CANCELED = 4;         //账户取消
    const OPERATION_STATE_CANCELED = 5;                 //运营取消
    const OPERATION_STATE_REMOVED = 6;                  //拆除
    const OPERATION_STATES = [
        self::OPERATION_STATE_MANUFACTURING => '运营',
        self::OPERATION_STATE_CONSTRUCTING => '在建',
        self::OPERATION_STATE_ABNORMALLY_MANUFACTURING => '异常运营',
        self::OPERATION_STATE_ACCOUNT_CANCELED => '账户取消',
        self::OPERATION_STATE_CANCELED => '运营取消',
        self::OPERATION_STATE_REMOVED => '拆除'
    ];

    use SoftDeletes, HasParkRate, HasParkSetting;

    protected $fillable = [
        'project_name', 'park_name', 'park_number', 'unique_code', 'company', 'property_id',
        'project_group_id', 'park_province', 'park_city', 'park_area', 'project_address',
        'longitude', 'latitude', 'entrance_coordinate', 'exit_coordinate', 'park_type',
        'park_cooperation_type', 'park_client_type', 'park_property', 'park_operation_state',
        'park_device_type', 'park_state', 'park_height_permitted', 'score'
    ];

    public function setParkHeightPermittedAttribute($value) {
        $this->attributes['park_height_permitted'] = $value * 100;
    }

    public function getParkHeightPermittedAttribute($value) {
        return $value / 100;
    }

    public static $logName = "park";

    public function stall()
    {
        return $this->hasOne(ParkStall::class);
    }

    public function parkStall()
    {
        return $this->hasOne(ParkStall::class, 'park_id','id');
    }

    public function projectGroup()
    {
        return $this->belongsTo(ProjectGroup::class, 'project_group_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function parkService()
    {
        return $this->hasOne(ParkService::class, 'park_id');
    }
    public function province()
    {
        return $this->hasOne(Province::class,'name','park_province');
    }
    public function city()
    {
        return $this->hasOne(City::class,'name','park_city');
    }
    public function country()
    {
        return $this->hasOne(Country::class,'name','park_area');
    }

    public function account()
    {
        return $this->hasOne(AccountManage::class,'park_id','id');
    }
    /**
     * 车场区域
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas()
    {
        return $this->hasMany('App\Models\Parks\ParkArea');
    }

    /**
     * 车位 通过区域将车位与停车场进行关联起来
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function spaces()
    {
        // return $this->hasManyThrough(ParkSpace::class, ParkArea::class);
        return $this->hasMany(ParkSpace::class);
    }

    /**
     * 地图SDK数据
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function map()
    {
        return $this->hasOne(CarportMap::class)->withDefault([
            'map_id' => '',
            'map_key' => ''
        ]);
    }

    // 可预约的车位
    public function reservedSpaces()
    {
        return $this->spaces()
            ->where('is_reserved_type', true)
            ->where('park_spaces.status', 1);
    }

    public function scopeOpen(Builder $query)
    {
        return $query->where('park_operation_state', true)
            ->where('park_state', true);
    }

    /**
     * 根据查询条件过滤查询范围
     * @param Builder $query
     * @param Request $request
     * @return  Builder
     */
    public function scopeSearch(Builder $query, Request $request)
    {
        if (!is_null($park_name = $request->input('park_name'))) {
            $query->where('park_name', "like", "%$park_name%");
        }
        if (!is_null($project_name = $request->input('project_name'))) {
            $query->where('project_name', 'like', "%$project_name%");
        }
        if ($area = $request->input('area')) {
            $query->where('park_area', '=', $area);
        } elseif ($city = $request->input('city')) {
            $query->where('park_city', '=', $city);
        } elseif ($province = $request->input('province')) {
            $query->where('park_province', '=', $province);
        }
        if (!is_null($park_state = $request->input('park_state'))) {
            $query->where('park_state', '=', $park_state);
        }
        if (!is_null($park_operation_state = $request->input('park_operation_state'))) {
            $query->where('park_operation_state', '=', $park_operation_state);
        }
        if (!is_null($project_group_name = $request->input('project_group_name'))) {
            $query->whereHas('projectGroup', function ($query) use ($project_group_name) {
                $query->where('group_name', 'like', "%$project_group_name%");
            });
        }
        return $query;
    }

    // 返回停车场的名称和id
    public function scopeParkInfo(Builder $query, Request $request)
    {
        // 判断是否有省
        if ($park_province = $request->post('park_province')) {
            $query->where('park_province', $park_province);
        }
        // 判断是否有市
        if ($park_city = $request->post('park_city')) {
            $query->where('park_city', $park_city);
        }
        // 判断是否有区
        if ($park_area = $request->post('park_area')) {
            $query->where('park_area', $park_area);
        }
        return $query;
    }

    /**
     * 关联停车场的费率
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parkRate()
    {
        return $this->hasMany(ParkRate::class);
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * scopeGeo
     *
     * @param Builder $query
     * @param array|string $locations 经度和纬度用","分割，经度在前，纬度在后
     * @param int $distance 范围长度 默认500米
     * @return Builder
     */
    public function scopeGeo(Builder $query, $locations, $distance = 500)
    {
        list($lng, $lat) = is_array($locations) ? $locations : explode(',', $locations);

        $sql = "6371 * ACOS (COS ( RADIANS ({$lat}) ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS({$lng}) ) + SIN ( RADIANS({$lat}) ) * SIN( RADIANS( latitude ) ) )";

        $query->select('*');

        $query->selectSub($sql, 'distance');

        // $query->having('distance', '<', $distance);

        $query->oldest('distance');

        return $query;
    }

    public function scopeSelectFee(Builder $query)
    {
        return $query->addSelect([
            'fee' => ParkStall::query()
                // ->select('fee_string')
                ->select('map_fee')
                ->whereColumn('park_stalls.park_id', 'parks.id')
        ]);
    }

    /**
     * scopeReservedSpaces
     *
     * @example reservedSpaces
     * @param Builder $query
     * @return Builder
     */
    public function scopeReservedSpaces(Builder $query)
    {
        return $query->withCount([
            'reservedSpaces',
            // 充电桩车位
            'reservedSpaces as charging_pile_count'=> function ($query) {
                $query->where('category', 1);
                $query->selectRaw("count(*)");
            }
        ]);
    }

    public function address()
    {
        return implode('-', $this->only('park_province', 'park_city', 'park_area', 'project_address'));
    }

    public function rentals()
    {
        return $this->hasMany(CarRent::class);
    }

    public function rates()
    {
        return $this->hasMany(ParkRate::class);
    }

    /**
     * comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(UserComment::class);
    }
}
