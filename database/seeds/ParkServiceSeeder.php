<?php

use Illuminate\Database\Seeder;

class ParkServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Parks\ParkService::class,10)->create();
    }
}
