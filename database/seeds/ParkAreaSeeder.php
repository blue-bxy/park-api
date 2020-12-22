<?php

use Illuminate\Database\Seeder;

class ParkAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parks = \App\Models\Parks\Park::query()->limit(10)->get();
        foreach ($parks as $park) {
            $this->areasPerPark($park);
        }
    }

    /**
     * 针对一个停车场生成对应的区域、车位、设备填充数据
     * @param $park
     */
    protected function areasPerPark($park) {
        $areas = factory(\App\Models\Parks\ParkArea::class, 2)
            ->create([
                'name' => $park->park_name.'xx区域',
                'park_id' => $park->id
            ])
            ->each(function ($area) {
                //新增车位
                $area->spaces()->createMany(
                    factory(\App\Models\Parks\ParkSpace::class, 20)
                        ->make(['park_id' => $area->park_id])->toArray()
                );
            });
    }

}
