<?php

use Illuminate\Database\Seeder;

class CarRentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(\App\Models\Dmanger\CarRent::class,20)->create();
    }
}
