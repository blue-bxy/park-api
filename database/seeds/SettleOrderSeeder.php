<?php

use Illuminate\Database\Seeder;

class SettleOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Financial\SettleOrder::class,20)->create();
    }
}
