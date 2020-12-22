<?php

use Illuminate\Database\Seeder;
use App\Models\Users\UserOrder;

class UserOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	{
		$data = factory(App\Models\Users\UserOrder::class, 20)->create();

		foreach ($data as $v){
		    $apt = factory(\App\Models\Dmanger\CarApt::class)->create([
		        'user_order_id' => $v->id,
		        'park_id' => $v->park_id,
            ]);
            $v->car_apt_id = $apt->id;

            factory(\App\Models\Dmanger\CarAptOrder::class)->create(['car_apt_id' => $apt->id]);

            $stop = factory(\App\Models\Dmanger\CarStop::class)->create([
                'user_order_id' => $v->id,
                'user_id' => $v->user_id,
                'park_id' => $v->park_id,
            ]);

		    $v->car_stop_id = $stop->id;
		    $v->user_car_id = $stop->user_car_id;

            $v->save();
		}
	}
}
