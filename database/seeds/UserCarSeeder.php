<?php

use Illuminate\Database\Seeder;

class UserCarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Users\UserCar::class, 20)->create();
    }
}
