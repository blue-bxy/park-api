<?php

use Illuminate\Database\Seeder;

class ParkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parks = factory(\App\Models\Parks\Park::class,10)->create();

        foreach ($parks as $park) {
            factory(\App\Models\Parks\ParkStall::class)->create([
                'park_id' => $park->id,
            ]);
            factory(\App\Models\Parks\ParkService::class)->create([
                'park_id' => $park->id,
            ]);
        }

    }
}
