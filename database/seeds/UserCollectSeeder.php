<?php

use Illuminate\Database\Seeder;

class UserCollectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Users\UserCollect::class, 20)->create();
    }
}
