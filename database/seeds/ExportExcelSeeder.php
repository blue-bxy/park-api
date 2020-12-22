<?php

use Illuminate\Database\Seeder;

class ExportExcelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Dmanger\ExportExcel::class,20)->create();
    }
}
