<?php

use Illuminate\Database\Seeder;

class WithDrawalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $withdrawals = factory(\App\Models\Financial\Withdrawal::class,10)->create();
    }
}
