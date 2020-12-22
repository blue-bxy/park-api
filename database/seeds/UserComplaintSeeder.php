<?php

use Illuminate\Database\Seeder;

class UserComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Users\UserComplaint::class, 20)->create();
    }
}
