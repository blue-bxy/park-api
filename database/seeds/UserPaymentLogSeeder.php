<?php

use Illuminate\Database\Seeder;

class UserPaymentLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Users\UserPaymentLog::class, 20)->create();
    }
}
