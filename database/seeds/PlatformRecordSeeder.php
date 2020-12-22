<?php

use Illuminate\Database\Seeder;

class PlatformRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Financial\PlatformFinancialRecord::class,20)->create();
    }
}
