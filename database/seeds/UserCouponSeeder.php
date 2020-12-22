<?php

use Illuminate\Database\Seeder;

class UserCouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Users\UserCoupon::class, 20)->create();

    }
}
