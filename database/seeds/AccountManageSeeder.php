<?php

use Illuminate\Database\Seeder;

class AccountManageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arr = factory(\App\Models\Financial\AccountManage::class,20)->create();
        foreach ($arr as $v){
            $v->sub_branch = $v->bank_name.'虹桥支行';
            $v->save();
        }
    }
}
