<?php

use Illuminate\Database\Seeder;

class ParkSpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Parks\ParkSpace::class,20)->create();
    }
}
