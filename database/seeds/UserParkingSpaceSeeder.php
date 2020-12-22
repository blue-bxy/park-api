<?php

use Illuminate\Database\Seeder;

class UserParkingSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Users\UserParkingSpace::class, 20)->create();
    }
}
