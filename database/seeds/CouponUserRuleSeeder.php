<?php

use Illuminate\Database\Seeder;

class CouponUserRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Coupons\CouponUserRule::class,20)->create();
    }
}
