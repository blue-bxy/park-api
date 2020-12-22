<?php

use Illuminate\Database\Seeder;

class CouponRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Coupons\CouponRule::class,20)->create();
    }
}
