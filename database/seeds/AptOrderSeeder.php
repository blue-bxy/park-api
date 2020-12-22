<?php

use Illuminate\Database\Seeder;

class AptOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Withdrawal\AptOrderDay::class,20)->create();
    }
}
