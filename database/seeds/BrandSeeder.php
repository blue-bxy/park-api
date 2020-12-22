<?php

use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i < 5; $i++) {
            $brand = \App\Models\Brand::query()->create([
                'name' => '索尼',
                'type' => $i
            ]);
            //添加型号
            $models = array();
            for ($j = 0; $j < 5; $j++) {
                $models[] = [
                    'name' => get_order_no()
                ];
            }
            $brand->models()->createMany($models);
        }
        $this->areaBrands();
    }

    /**
     * 为每个区域添加设备品牌
     */
    protected function areaBrands() {
        $areas = \App\Models\Parks\ParkArea::query()->select('id')->get();
        $brands = \App\Models\Brand::query()->select('id')->get();
        $data = array();
        foreach ($areas as $area) {
            foreach ($brands as $brand) {
                $data[] = [
                    'park_area_id' => $area['id'],
                    'brand_id' => $brand['id']
                ];
            }
        }
        \Illuminate\Support\Facades\DB::table('park_area_brands')->insert($data);
    }
}
