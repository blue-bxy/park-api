<?php

use App\Models\Financial\Record;
use Illuminate\Database\Seeder;

class RecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $record = factory(\App\Models\Financial\Record::class,10)->create();
    }
}
