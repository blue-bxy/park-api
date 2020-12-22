<?php

use Illuminate\Database\Seeder;

class CarStopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Dmanger\CarStop::class,20)->create();
    }
}
