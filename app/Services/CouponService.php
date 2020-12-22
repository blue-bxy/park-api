<?php


namespace App\Services;


use App\Exceptions\InvalidArgumentException;
use App\Models\Coupons\Coupon;
use App\Models\Coupons\CouponParkRule;
use App\Models\Coupons\CouponRule;
use App\Models\Coupons\CouponUserRule;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CouponService {
    public function store(array $data) {
        $data = array_merge($data, [
            'no' => get_order_no(),
            'is_valid' => $data['is_valid'] ?? 1,
            'take_count' => 0,
            'used_count' => 0,
            'expired_at' => $data['valid_end_time']
        ]);
        $data = $data['distribution_method'] == 5 ? $this->fast($data) : $this->normal($data);
//        $data = $data['method'] == 'fast' ? $this->fast($data) : $this->normal($data);
        $data = array_merge($data, [
            'use_scene' => $data['rules']['rule']['use_scene'] ?? 0,
            'used_amount' => $data['rules']['rule']['amount'] ?? 0,
            'coupon_rule_type' => $data['rules']['rule']['type'] ?? 0,
            'coupon_rule_value' => $data['rules']['rule']['value'] ?? null
        ]);
        $coupon = new Coupon($data);
        $coupon->createQrcodeData();
        $coupon->save();
        $this->publish($coupon);
    }

    /**
     * 分发优惠券
     * @param Coupon $coupon
     */
    public function publish(Coupon $coupon) {
        $users = $this->users($coupon);
        $data = array();
        $count = 0;
        foreach ($users as $user) {
            $data[] = [
                'no' => $coupon->no.str_pad(++$count, 5),
                'user_id' => $user->id,
                'coupon_id' => $coupon->id,
                'title' => $coupon->title,
                'park_id' => $coupon->park_id ?? 0,
                'amount' => 0,
                'start_time' => $coupon->start_time,
                'end_time' => $coupon->end_time,
                'expiration_time' => $coupon->expired_at,
                'distribution_method' => $coupon->distribution_method,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        DB::table('user_coupons')->insert($data);
        $coupon->take_count += $count;
        $coupon->save();
    }

    /**
     * 根据用户规则筛选用户
     * @param Coupon $coupon
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function users(Coupon $coupon) {
        $query = User::query();
        if ($coupon->assign_user) {
            return $query->whereIn('mobile', $coupon->assign_user)->get();
        }
        $userRule = $coupon->couponUserRule;
        if ($userRule->is_activity_active) {
            $query->whereHas('loginLogs', function (Builder $query) use ($userRule, $coupon) {
                $query->whereDate('updated_at', '>=', date('Y-m-d',
                    strtotime("- $userRule->activity_setting_days day", strtotime($coupon->start_time))))
                    ->groupBy('user_id')->havingRaw('count(*) >= '.$userRule->active_days);
            });
        }
        if ($userRule->is_regression_active) {
            $query->where('updated_at', '<',
                date('Y-m-d H:i:s', strtotime("- $userRule->regression_days day", strtotime($coupon->start_time))));
        }
        if ($userRule->is_new_user) {
            //todo
        }
        return $query->get();
    }

    /**
     * 批量发布数据处理
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function normal(array &$data) {
        if (empty($rule = CouponRule::query()->find($data['coupon_rule_id']))) {
            throw new InvalidArgumentException('优免规则不存在！');
        }
        if (empty($parkRule = CouponParkRule::query()->find($data['coupon_park_rule_id']))) {
            throw new InvalidArgumentException('车场规则不存在！');
        }
        if (empty($userRule = CouponUserRule::query()->find($data['coupon_user_rule_id']))) {
            throw new InvalidArgumentException('用户规则不存在！');
        }
        $data['rules'] = [
            'rule' => [
                'title' => $rule->title,
                'amount' => $rule->amount,
                'use_scene' => $rule->use_scene,
                'type' => $rule->type,
                'value' => $rule->value
            ],
            'park' => [
                'title' => $parkRule->title,
                'province_id' => $parkRule->province_id,
                'city_id' => $parkRule->city_id,
                'district_id' => $parkRule->district_id,
                'park_property' => $parkRule->park_property
            ],
            'user' => [
                'title' => $userRule->title,
                'is_activity_active' => $userRule->is_activity_active,
                'active_setting_days' => $userRule->active_setting_days,
                'active_days' => $userRule->active_days,
                'is_regression_active' => $userRule->is_regression_active,
                'regression_days' => $userRule->regression_days,
                'is_new_user' => $userRule->is_new_user
            ]
        ];
        return $data;
    }

    /**
     * 快速发布数据处理
     * @param array $data
     * @return array
     * @throws InvalidArgumentException
     */
    public function fast(array &$data) {
        if (empty($rule = Coupon::query()->find($data['coupon_rule_id']))) {
            throw new InvalidArgumentException('优免规则不存在！');
        }
        if (empty($parkRule = CouponParkRule::query()->find($data['coupon_park_rule_id']))) {
            throw new InvalidArgumentException('车场规则不存在！');
        }
        $data = array_merge($data, [
            'quota' => count($data['assign_user']) * $data['max_receive_num'],
            'start_time' => now(),
            'end_time' => $data['valid_end_time'],
//            'distribution_method' => 1,
            'rules' => [
                'rule' => [
                    'title' => $rule->title,
                    'amount' => $rule->amount,
                    'use_scene' => $rule->use_scene,
                    'type' => $rule->type,
                    'value' => $rule->value
                ],
                'park' => [
                    'title' => $parkRule->title,
                    'province_id' => $parkRule->province_id,
                    'city_id' => $parkRule->city_id,
                    'district_id' => $parkRule->district_id,
                    'park_property' => $parkRule->park_property
                ],
                'user' => []
            ]
        ]);
        return $data;
    }

}
