<?php

use Illuminate\Database\Seeder;

class BadCreditsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Financial\BadCredit::class,20)->create();
    }
}
