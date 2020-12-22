<?php

use Illuminate\Database\Seeder;

class UserIntegralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Users\UserIntegral::class, 20)->create();
    }
}
