<?php

use Illuminate\Database\Seeder;

class ParkStallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Parks\ParkStall::class)->create();
    }
}
