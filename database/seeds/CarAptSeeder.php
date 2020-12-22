<?php

use Illuminate\Database\Seeder;

class CarAptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = factory(\App\Models\Dmanger\CarApt::class,20)->create();

        foreach ($data as $v){
            factory(\App\Models\Dmanger\CarAptOrder::class)->create(['car_apt_id' => $v->id]);
        }
        
    }
}
