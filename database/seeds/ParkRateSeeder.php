<?php

use Illuminate\Database\Seeder;

class ParkRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parks = \App\Models\Parks\Park::all();
        foreach ($parks as $park) {
            $publisher_id = $park->property->id;
            factory(\App\Models\Parks\ParkRate::class, 1)
                ->create([
                    'park_id' => $park->id,
                    'park_area_id' => 0,
                    'publisher_type' => \App\Models\Property::class,
                    'publisher_id' => $publisher_id
                ])->each(function ($rate) {
                    $this->bindSpaces($rate);
                });
        }
    }

    /**
     * 绑定车位
     * @param $rate
     */
    private function bindSpaces($rate) {
        $spaces = \App\Models\Parks\ParkSpace::query()
            ->where('park_id', '=', $rate->park_id)
            ->where('status', '=', 0)
            ->pluck('id')->toArray();
        shuffle($spaces);
        $spaces = array_slice($spaces, 0, $rate->parking_spaces_count);
        $data = [];
        foreach ($spaces as $space) {
            $data[] = [
                'model_type' => \App\Models\Parks\ParkSpace::class,
                'model_id' => $space,
                'park_rate_id' => $rate->id
            ];
        }
        DB::table('model_has_park_rates')->insert($data);
        DB::table('park_spaces')
            ->whereIn('id', $spaces)
            ->update(['status' => 1]);
    }
}
