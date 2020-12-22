<?php

use Illuminate\Database\Seeder;

class CouponParkRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Coupons\CouponParkRule::class,20)->create();
    }
}
