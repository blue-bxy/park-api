<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $province_path = storage_path('regions/province.json');
        $city_path = storage_path('regions/city.json');
        $country_path = storage_path('regions/country.json');

        $provinces = json_decode(file_get_contents($province_path), true);
        foreach ($provinces as $province) {
            $p_model = new \App\Models\Regions\Province([
                'name' => $province['name'],
                'province_id' => $province['id']
            ]);

            $p_model->save();

            $cities = json_decode(file_get_contents($city_path), true);
            if (!empty($cities[$province['id']])) {
                foreach ($cities[$province['id']] as $city) {
                    $c_model = $p_model->city()->create([
                        'name' => $city['name'],
                        'city_id' => $city['id'],
                    ]);

                    $counties = json_decode(file_get_contents($country_path), true);
                    if (!empty($counties[$city['id']])) {
                        foreach ($counties[$city['id']] as $country) {
                            $c_model->country()->create([
                                'name' => $country['name'],
                                'country_id' => $country['id']
                            ]);
                        }
                    }

                }
            }


        }


    }
}
