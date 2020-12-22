<?php

use Illuminate\Database\Seeder;

class CarAptOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Dmanger\CarAptOrder::class,20)->create();

    }
}
