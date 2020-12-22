<?php

use Illuminate\Database\Seeder;

class UserConsumptionRecodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Users\UserConsumptionRecodes::class, 20)->create();
    }
}
